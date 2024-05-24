<?php

namespace App\Repositories\Message;

use App\Repositories\Message\Interfaces\ChatRepositoryInterface;

class ChatRepository implements ChatRepositoryInterface
{
    public function getById(int $chatId): ?array
    {
        return [];
    }

    public function getByUserId(int $userId): array
    {
        return [];
    }
}
