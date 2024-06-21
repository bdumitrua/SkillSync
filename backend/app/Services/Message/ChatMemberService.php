<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\User;
use App\Models\GroupChatMember;
use App\Models\Chat;
use App\Http\Resources\User\UserDataResource;
use App\Exceptions\UnprocessableContentException;

class ChatMemberService implements ChatMemberServiceInterface
{
    protected $userRepository;
    protected $teamRepository;
    protected $chatMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        ChatMemberRepositoryInterface $chatMemberRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->chatMemberRepository = $chatMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function show(Chat $chat): JsonResource
    {
        if (Gate::denies('view', [Chat::class, $chat])) {
            return new JsonResource([]);
        }

        $chatMemberIds = $this->chatMemberRepository->getByChat($chat);
        $chatMemberData = $this->userRepository->getByIds($chatMemberIds);

        return UserDataResource::collection($chatMemberData);
    }

    /**
     * @throws UnprocessableContentException if $chat->type is dialog
     */
    public function add(Chat $chat, User $newUser): void
    {
        if ($chat->isDialog()) {
            throw new UnprocessableContentException("You can't change dialog members");
        }

        /** @var GroupChatMember|null */
        $chatMembership = $this->chatMemberRepository->getByBothIds($chat->id, $newUser->id);
        Gate::authorize('create', [GroupChatMember::class, $chat, $newUser, $chatMembership]);

        $this->chatMemberRepository->create($chat->id, $newUser->id);
    }

    public function delete(Chat $chat, User $userToDelete): void
    {
        if ($chat->isDialog()) {
            throw new UnprocessableContentException("You can't change dialog members");
        }

        /** @var GroupChatMember|null */
        $chatMembership = $this->chatMemberRepository->getByBothIds($chat->id, $userToDelete->id);
        Gate::authorize('delete', [GroupChatMember::class, $chat, $userToDelete, $chatMembership]);

        $this->chatMemberRepository->delete($chatMembership);
    }
}
