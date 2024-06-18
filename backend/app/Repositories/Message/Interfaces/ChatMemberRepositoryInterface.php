<?php

namespace App\Repositories\Message\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\GroupChatMember;
use App\Models\Chat;

interface ChatMemberRepositoryInterface
{
    /**
     * @param Chat $chat
     * 
     * @return array
     */
    public function getByChat(Chat $chat): array;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param int $chatId
     * @param int $userId
     * 
     * @return GroupChatMember|null
     */
    public function getByBothIds(int $chatId, int $userId): ?GroupChatMember;

    /**
     * @param int $chatId
     * @param array $userIds
     * 
     * @return void
     */
    public function addMembersToChat(int $chatId, array $userIds): void;

    /**
     * @param int $chatId
     * @param int $userId
     * 
     * @return void
     */
    public function create(int $chatId, int $userId): void;

    /**
     * @param GroupChatMember $groupChatMember
     * 
     * @return void
     */
    public function delete(GroupChatMember $groupChatMember): void;
}
