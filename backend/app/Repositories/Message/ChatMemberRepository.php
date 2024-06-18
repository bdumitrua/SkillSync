<?php

namespace App\Repositories\Message;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\GroupChatMember;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;

class ChatMemberRepository implements ChatMemberRepositoryInterface
{
    protected function getGroupChatByChatId(int $chatId): ?GroupChat
    {
        return GroupChat::where('chat_id', '=', $chatId)->first();
    }

    public function getByChat(Chat $chat): array
    {
        return $chat->membersIds();
    }

    public function getIdsByUserId(int $userId): array
    {
        Log::debug('Starting to collect chatIds by userId', [
            'userId' => $userId
        ]);

        // Получаем ID и время обновления групповых чатов
        $groupChatMemberships = GroupChatMember::where('user_id', '=', $userId)->latest()
            ->get(['group_chat_id'])
            ->pluck('group_chat_id')
            ->toArray();

        $chatIds = new Collection([]);
        if (!empty($groupChatMemberships)) {
            $chatIds = GroupChat::whereIn('id', $groupChatMemberships)
                ->get(['chat_id', 'updated_at']);

            Log::debug('User is member of group chats', [
                'chatIds' => $chatIds->toArray()
            ]);
        }


        // Получаем ID и время обновления диалогов
        $dialogIds = DialogChat::where(function (Builder $query) use ($userId) {
            $query->where('first_user_id', '=', $userId)
                ->orWhere('second_user_id', '=', $userId);
        })->get(['chat_id', 'updated_at']);

        Log::debug('User is member of dialog chats', [
            'dialogIds' => $dialogIds->toArray()
        ]);

        // Объединяем результаты и сортируем по updated_at
        $combined = (new Collection([...$chatIds, ...$dialogIds]))->sortByDesc('updated_at');

        // Извлекаем только ID чатов
        $chatIds = $combined->pluck('chat_id')->toArray();

        Log::debug('Finished to collect chatIds by userId', [
            'userId' => $userId,
            'chatIds' => $chatIds
        ]);

        return $chatIds;
    }

    public function getByBothIds(int $chatId, int $userId): ?GroupChatMember
    {
        $groupChat = $this->getGroupChatByChatId($chatId);

        if (empty($groupChat)) {
            return null;
        }

        return GroupChatMember::where('group_chat_id', '=', $groupChat->id)
            ->where('user_id', '=', $userId)
            ->first();
    }

    public function dialogExists(array $memberIds): bool
    {
        return DialogChat::whereIn('first_user_id', $memberIds)
            ->whereIn('second_user_id', $memberIds)
            ->exists();
    }

    public function addMembersToChat(int $chatId, array $userIds): void
    {
        Log::debug('Preparing data for new GroupChatMembers', [
            'chatId' => $chatId,
            'userIds' => $userIds,
        ]);

        $groupChat = $this->getGroupChatByChatId($chatId);
        $newMembers = [];

        foreach ($userIds as $userId) {
            $newMembers[] = [
                'group_chat_id' => $groupChat->id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Log::debug('Inserting data to create new GroupChatMembers', [
            'newMembers' => $newMembers
        ]);

        GroupChatMember::insert($newMembers);
    }

    public function create(int $chatId, int $userId): void
    {
        $groupChat = $this->getGroupChatByChatId($chatId);

        GroupChatMember::create([
            'group_chat_id' => $groupChat->id,
            'user_id' => $userId
        ]);
    }

    public function delete(GroupChatMember $groupChatMember): void
    {
        $groupChatMember->delete();
    }
}
