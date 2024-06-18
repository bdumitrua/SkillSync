<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\User;
use App\Models\DialogChat;
use App\Models\Chat;
use App\DTO\Message\CreateChatDTO;

class DialogChatPolicy
{
    protected $chatMemberRepository;

    public function __construct(
        ChatMemberRepositoryInterface $chatMemberRepository
    ) {
        $this->chatMemberRepository = $chatMemberRepository;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        $memberIds = $this->chatMemberRepository->getByChat($chat);

        return in_array($user->id, $memberIds)
            ? Response::allow()
            : Response::deny("You're not member of this chat.", 403);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, CreateChatDTO $createChatDTO): Response
    {
        $dialogMemberIds = [$createChatDTO->firstUserId, $createChatDTO->secondUserId];
        if (!in_array($user->id, $dialogMemberIds)) {
            return Response::deny("You can't create dialogs for other users", 422);
        }

        if ($dialogMemberIds[0] === $dialogMemberIds[1]) {
            return Response::deny("You can't create dialog with yourself.");
        }

        return $this->chatMemberRepository->dialogExists($dialogMemberIds)
            ? Response::deny('Dialog between you already exists', 400)
            : Response::allow();
    }
}
