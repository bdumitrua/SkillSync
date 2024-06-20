<?php

namespace App\Repositories\Message;

use Illuminate\Support\Collection;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Models\NoSQL\Message;
use App\Firebase\FirebaseServiceInterface;
use App\Events\MessageSentEvent;
use App\Events\MessageReadEvent;
use App\Events\MessageDeleteEvent;
use App\DTO\Message\CreateMesssageDTO;

class MessageRepository implements MessageRepositoryInterface
{
    protected $firebaseService;

    public function __construct(
        FirebaseServiceInterface $firebaseService
    ) {
        $this->firebaseService = $firebaseService;
    }

    public function getByChatId(int $chatId): Collection
    {
        return new Collection($this->firebaseService->getChatMessages($chatId));
    }

    public function search(string $query): Collection
    {
        return Message::search($query);
    }

    public function create(int $chatId, string $newMessageUuid, CreateMesssageDTO $createMesssageDTO): array
    {
        $newMessageData = $this->firebaseService->sendMessage($chatId, $newMessageUuid, $createMesssageDTO);

        // TODO
        // I know it's bad, fix later
        $message = new Message(
            $newMessageUuid,
            $chatId,
            $newMessageData['text'],
            $newMessageData['status'],
            $newMessageData['senderId'],
            $newMessageData['created_at']
        );

        Message::addToElasticsearch($message, 'uuid');

        event(new MessageSentEvent($chatId, $message));

        return $newMessageData;
    }

    public function read(int $chatId, string $messageUuid): void
    {
        $this->firebaseService->readMessage($chatId, $messageUuid);
        Message::readElasticsearchMessage($messageUuid);

        event(new MessageReadEvent($chatId, $messageUuid));
    }

    public function delete(int $chatId, string $messageUuid): void
    {
        $this->firebaseService->deleteMessage($chatId, $messageUuid);
        Message::deleteElasticsearchDocument($messageUuid, 'uuid');

        event(new MessageDeleteEvent($chatId, $messageUuid));
    }
}
