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
        $newMessageRef->set($messageData->toArray());

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
        $chatMessagesRef = $this->database->getReference($this->getChatMessagesPath($chatId));
        $snapshot = $chatMessagesRef->getSnapshot();

        return $snapshot->getValue() ?? [];
    }

    public function getChatsMessages(array $chatIds): array
    {
        $messages = [];

        // Создаем массив путей для мульти-запроса
        $paths = [];
        foreach ($chatIds as $chatId) {
            $paths[$chatId] = $this->getChatMessagesPath($chatId);
        }

        // Выполняем мульти-запрос к базе данных
        $chatMessagesRef = $this->database->getReference();
        $snapshots = $chatMessagesRef->getSnapshot()->getValue($paths);

        // Обрабатываем результаты запроса
        foreach ($chatIds as $chatId) {
            $messages[$chatId] = $snapshots[$chatId] ?? [];
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
