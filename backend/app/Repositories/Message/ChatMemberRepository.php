<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\GroupChatMember;
use App\Models\Chat;

class ChatMemberRepository implements ChatMemberRepositoryInterface
{
    public function getByChat(Chat $chat): array
    {
        return [];
    }

    public function getByUserId(int $userId): Collection
    {
        return new Collection([]);
    }

    public function getByBothIds(int $chatId, int $userId): ?GroupChatMember
    {
        return null;
    }

    public function dialogExists(array $memberIds): bool
    {
        return false;
    }

    public function addMembersToChat(int $chatId, array $userIds): void
    {
        // 
    }

    public function create(int $chatId, int $userId): void
    {
        // 
    }

    public function delete(GroupChatMember $groupChatMember): void
    {
        // 
    }
}
