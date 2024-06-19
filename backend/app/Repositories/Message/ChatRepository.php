<?php

namespace App\Repositories\Message;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

class ChatRepository implements ChatRepositoryInterface
{
    use Updateable;

    public function getChatData(Chat $chat)
    {
        return $chat->data()->first();
    }

    public function getDataByIds(array $chatIds): Collection
    {
        Log::debug('Getting chats data by ids', [
            'chatIds' => $chatIds
        ]);

        $chats = Chat::whereIn('id', $chatIds)->get();

        $dialogs = DialogChat::whereIn('chat_id', $chatIds)->get()->keyBy('chat_id');
        $groups = GroupChat::whereIn('chat_id', $chatIds)->get()->keyBy('chat_id');

        $chats = $chats->each(function ($chat) use ($dialogs, $groups) {
            $chat->data = $chat->type == 'dialog' ? $dialogs->get($chat->id) : $groups->get($chat->id);
        })->sortByDesc('updated_at');;

        return $chats;
    }

    public function search(string $query): Collection
    {
        $dialogChats = DialogChat::search($query);
        $groupChats = GroupChat::search($query);

        return $dialogChats->merge($groupChats);
    }

    public function create(CreateChatDTO $createChatDTO): Chat
    {
        Log::debug('Start creating chat from dto', [
            'createChatDTO' => $createChatDTO
        ]);

        $newChat = Chat::create(
            $createChatDTO->toArray(creatingBasicChat: true)
        );

        $createChatDTO->setChatId($newChat->id);

        Log::debug('Created base Chat, going to create typed chat', [
            'createChatDTO' => $createChatDTO
        ]);

        if ($createChatDTO->isDialog()) {
            $dialogChat = DialogChat::create(
                $createChatDTO->toArray()
            );
            Log::debug('Created DialogChat', [
                'dialogChat' => $dialogChat->toArray()
            ]);
        } else {
            $groupChat = GroupChat::create(
                $createChatDTO->toArray()
            );
            Log::debug('Created GroupChat', [
                'groupChat' => $groupChat->toArray()
            ]);
        }

        return $newChat;
    }

    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void
    {
        /** @var GroupChat */
        $groupChat = $this->getChatData($chat);

        $this->updateFromDto($groupChat, $updateChatDTO);
    }
}
