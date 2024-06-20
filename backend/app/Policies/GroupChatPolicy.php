<?php

namespace App\Policies;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\GroupChat;
use App\Models\Chat;
use App\DTO\Message\CreateChatDTO;

class GroupChatPolicy
{
    protected $chatRepository;
    protected $chatMemberRepository;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        ChatMemberRepositoryInterface $chatMemberRepository,
    ) {
        $this->chatRepository = $chatRepository;
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
        if ($createChatDTO->isTeamChat()) {
            $canTouchTeamChats = Gate::inspect(TOUCH_TEAM_CHAT_GATE, [Team::class, $createChatDTO->adminId]);

            if ($canTouchTeamChats->denied()) {
                return $canTouchTeamChats;
            }

            $teamChat = $this->chatRepository->getChatByTeamId($createChatDTO->adminId);
            if (!empty($teamChat)) {
                return Response::deny("Chat, associated with this team, already exists.", 409);
            }
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Chat $chat): Response
    {
        /** @var GroupChat */
        $groupChat = $this->chatRepository->getChatData($chat);

        if ($groupChat->isTeamChat()) {
            return Gate::inspect(TOUCH_TEAM_CHAT_GATE, [Team::class, $groupChat->admin_id]);
        } else {
            return $groupChat->createdByUser($user->id)
                ? Response::allow()
                : Response::deny("Access denied.", 403);
        }
    }
}
