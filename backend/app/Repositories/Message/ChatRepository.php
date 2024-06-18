<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Models\GroupChat;
use App\Models\Chat;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

class ChatRepository implements ChatRepositoryInterface
{
    public function getChatData(Chat $chat)
    {
        return new GroupChat();
    }

    public function getDataByIds(array $chatIds): Collection
    {
        return new Collection([]);
    }

    public function getByUserId(int $userId): array
    {
        return [];
    }

    public function create(CreateChatDTO $createChatDTO): Chat
    {
        return new Chat();
    }

    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void
    {
        // 
    }
}
