<?php

namespace App\Firebase;

use Kreait\Firebase\Database;
use App\Firebase\FirebaseServiceInterface;
use App\Exceptions\NotFoundException;
use App\Enums\MessageStatus;
use App\DTO\Message\CreateMesssageDTO;

class FirebaseService implements FirebaseServiceInterface
{
    protected Database $database;
    protected string $bucket;

    public function __construct()
    {
        $this->database = app('firebase.database');
        $this->bucket = config('firebase.projects.app.storage.bucket');
    }

    public function measureLatency(): float
    {
        $startTime = microtime(true);

        // Пример запроса к базе данных
        $reference = $this->database->getReference($this->bucket);
        $snapshot = $reference->getSnapshot();

        $endTime = microtime(true);

        return ($endTime - $startTime) * 1000; // Возвращаем время в миллисекундах
    }

    public function wipeMyData(): bool
    {
        $bucketReference = $this->database->getReference($this->bucket);
        if (!$bucketReference->getSnapshot()->exists()) {
            return false;
        }

        $bucketReference->remove();
        return true;
    }

    public function sendMessage(int $chatId, string $newMessageUuid, CreateMesssageDTO $messageData): array
    {
        $newMessageRef = $this->database->getReference($this->getMessagePath($chatId, $newMessageUuid));

        // Запись данных в базу данных
        $newMessageData = array_merge(
            $messageData->toArray(),
            ['created_at' => Database::SERVER_TIMESTAMP]
        );

        $newMessageRef->set($newMessageData);

        // Получение обратно данных сообщения
        $newMessageSnapshot = $newMessageRef->getSnapshot();

        return (array) $newMessageSnapshot->getValue();
    }

    public function readMessage(int $chatId, string $messageUuid): void
    {
        $messageRef = $this->database->getReference($this->getMessagePath($chatId, $messageUuid));
        $snapshot = $messageRef->getSnapshot();

        if (!$snapshot->exists()) {
            throw new NotFoundException("Message");
        }

        $messageData = $snapshot->getValue();
        if ($messageData['status'] === MessageStatus::Sended) {
            $messageRef->update(['status' => MessageStatus::Readed]);
        }
    }

    public function deleteMessage(int $chatId, string $messageUuid): void
    {
        $messageRef = $this->database->getReference($this->getMessagePath($chatId, $messageUuid));
        $snapshot = $messageRef->getSnapshot();

        if (!$snapshot->exists()) {
            throw new NotFoundException("Message");
        }

        $messageRef->remove();
    }

    public function getChatMessages(int $chatId): array
    {
        $chatMessagesRef = $this->database->getReference($this->getChatMessagesPath($chatId))
            ->orderByChild('created_at')
            ->limitToLast(15);

        $snapshot = $chatMessagesRef->getSnapshot();
        return $snapshot->getValue() ?? [];
    }

    public function getChatsMessages(array $chatIds): array
    {
        $messages = [];

        foreach ($chatIds as $chatId) {
            $chatMessages = $this->getChatMessages($chatId);
            $messages[$chatId] = $chatMessages;
        }

        return $messages;
    }


    protected function getChatMessagesPath(int $chatId): string
    {
        return $this->bucket . "/messages/{$chatId}";
    }

    protected function getMessagePath(int $chatId, string $messageUuid): string
    {
        return $this->bucket . "/messages/{$chatId}/{$messageUuid}";
    }
}
