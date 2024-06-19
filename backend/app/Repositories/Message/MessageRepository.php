<?php

namespace App\Repositories\Message;

use Illuminate\Support\Collection;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
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

    public function create(int $chatId, string $newMessageUuid, CreateMesssageDTO $createMesssageDTO): array
    {
        $newMessageData = $this->firebaseService->sendMessage($chatId, $newMessageUuid, $createMesssageDTO);
        event(new MessageSentEvent($chatId, $newMessageData));

        return $newMessageData;
    }

    public function read(int $chatId, string $messageUuid): void
    {
        $this->firebaseService->readMessage($chatId, $messageUuid);
        event(new MessageReadEvent($chatId, $messageUuid));
    }

    public function delete(int $chatId, string $messageUuid): void
    {
        $this->firebaseService->deleteMessage($chatId, $messageUuid);
        event(new MessageDeleteEvent($chatId, $messageUuid));
    }
}
