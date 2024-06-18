<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\GroupChatMember;
use App\Models\GroupChat;
use App\Models\Chat;

class GroupChatMemberPolicy
{
    protected $chatRepository;
    protected $teamRepository;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        TeamRepositoryInterface $teamRepository,
    ) {
        $this->chatRepository = $chatRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Chat $chat, User $newUser, ?GroupChatMember $chatMembership): Response
    {
        if (!empty($chatMembership)) {
            return Response::deny("User is already member of this chat.");
        }

        $canUpdateChat = Gate::inspect('update', [GroupChat::class, $chat]);
        if ($canUpdateChat->denied()) {
            return $canUpdateChat;
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat, User $userToDelete, ?GroupChatMember $chatMembership): Response
    {
        if (empty($chatMembership)) {
            return Response::deny("User is not member of this chat.");
        }

        $canUpdateChat = Gate::inspect('update', [GroupChat::class, $chat]);
        if ($canUpdateChat->denied()) {
            return $canUpdateChat;
        }

        /** @var GroupChat */
        $groupChat = $this->chatRepository->getChatData($chat);
        if ($groupChat->isTeamChat()) {
            $team = $this->teamRepository->getById($groupChat->admin_id);
            $userToDeleteIsTeamAdmin = $userToDelete->id === $team->admin_id;

            if ($userToDeleteIsTeamAdmin) {
                return Response::deny("You have insufficient rigths.");
            }
        } elseif ($groupChat->isUserGroupChat()) {
            if ($userToDelete->id === $groupChat->admin_id) {
                return Response::deny("Chat admin can't be removed from chat.");
            }
        }

        return Response::allow();
    }
}
