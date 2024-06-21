<?php

namespace App\Firebase;

use Kreait\Firebase\Database;
use Illuminate\Database\Eloquent\Collection;
use App\Models\NoSQL\Message;
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

    public function sendMessage(int $chatId, string $newMessageUuid, CreateMesssageDTO $messageData): Message
    {
        $newMessageRef = $this->database->getReference($this->getMessagePath($chatId, $newMessageUuid));

        // Запись данных в базу данных
        $newMessageData = array_merge(
            $messageData->toArray(),
            ['created_at' => Database::SERVER_TIMESTAMP]
        );

        $newMessageRef->set($newMessageData);

        // Получение обратно данных сообщения
        $newMessage = $newMessageRef->getSnapshot()->getValue();

        return $this->createMessageObject($newMessageUuid, $chatId, $newMessage);
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

    public function getChatMessages(int $chatId): Collection
    {
        $chatMessagesRef = $this->database->getReference($this->getChatMessagesPath($chatId))
            ->orderByChild('created_at')
            ->limitToLast(15);

        $messagesData = $chatMessagesRef->getSnapshot()->getValue() ?? [];
        $messages = new Collection([]);

        foreach ($messagesData as $uuid => &$messageData) {
            $messageData['uuid'] = $uuid;
            $messages->push($this->createMessageObject($messageData['uuid'], $chatId, $messageData));
        }

        return $messages;
    }

    public function getChatsMessages(array $chatIds): Collection
    {
        $messages = new Collection();

        foreach ($chatIds as $chatId) {
            $chatMessages = $this->getChatMessages($chatId);
            $messages->put($chatId, $chatMessages);
        }

        return $messages;
    }

    protected function createMessageObject(string $messageUuid, int $chatId, $data): Message
    {
        return new Message(
            $messageUuid,
            $chatId,
            $data['text'],
            $data['status'],
            $data['senderId'],
            $data['created_at'],
        );
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
