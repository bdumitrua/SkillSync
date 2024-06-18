<?php

namespace App\Repositories\Message;

use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\DTO\Message\CreateMesssageDTO;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(int $chatId, string $newMessageUuid, CreateMesssageDTO $createMesssageDTO): array
    {
        return [];
    }

    public function read(int $chatId, string $messageUuid): void
    {
        // 
    }

    public function delete(int $chatId, string $messageUuid): void
    {
        // 
    }
}
