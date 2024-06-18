<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;
use App\Helpers\StringHelper;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateMesssageDTO;
use App\DTO\Message\CreateChatDTO;

class ChatPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return $chat->isDialog()
            ? Gate::inspect('view', [DialogChat::class, $chat])
            : Gate::inspect('view', [GroupChat::class, $chat]);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, CreateChatDTO $createChatDTO): Response
    {
        return $createChatDTO->isDialog()
            ? Gate::inspect('create', [DialogChat::class, $createChatDTO])
            : Gate::inspect('create', [GroupChat::class, $createChatDTO]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chat): Response
    {
        if ($chat->isDialog()) {
            return Response::deny("You can't change data of dialog type chat", 405);
        }

        return Gate::inspect('update', [GroupChat::class, $chat]);
    }

    public function sendMessage(User $user, Chat $chat, CreateMesssageDTO $createMesssageDTO): Response
    {
        if ($user->id !== $createMesssageDTO->senderId) {
            return Response::deny("You can't send a message on behalf of another user.", 403);
        }

        return $this->view($user, $chat);
    }

    public function readMessage(User $user, Chat $chat, string $messageUuid): Response
    {
        $canView = $this->view($user, $chat);
        if ($canView->denied()) {
            return $canView;
        }

        $messageSenderId = StringHelper::decodeMessageUuid($messageUuid)['sender_id'];
        if ($user->id === $messageSenderId) {
            return Response::deny("You can't mark your own messages as reeded", 400);
        }

        return Response::allow();
    }

    public function deleteMessage(User $user, Chat $chat, string $messageUuid): Response
    {
        $canView = $this->view($user, $chat);
        if ($canView->denied()) {
            return $canView;
        }

        $messageSenderId = StringHelper::decodeMessageUuid($messageUuid)['sender_id'];
        if ($user->id !== $messageSenderId) {
            return Response::deny("You can't delete messages sended by other users", 403);
        }

        return Response::allow();
    }
}
