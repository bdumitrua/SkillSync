<?php

namespace App\Repositories\Message;

use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;

class ChatMemberRepository implements ChatMemberRepositoryInterface
{
    public function getByChatId(int $chatId): ?array
    {
        return [];
    }
}
