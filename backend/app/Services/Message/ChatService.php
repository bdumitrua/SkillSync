<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Traits\AttachEntityData;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Http\Requests\Message\CreateChatRequest;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

class ChatService implements ChatServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $teamRepository;
    protected $chatRepository;
    protected $messageRepository;
    protected $chatMemberRepository;
    protected $teamMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        ChatRepositoryInterface $chatRepository,
        MessageRepositoryInterface $messageRepository,
        ChatMemberRepositoryInterface $chatMemberRepository,
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->chatRepository = $chatRepository;
        $this->messageRepository = $messageRepository;
        $this->chatMemberRepository = $chatMemberRepository;
        $this->teamMemberRepository = $teamMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $chatIds = $this->chatMemberRepository->getByUserId($this->authorizedUserId)
            ->pluck('chat_id')->toArray();

        $chatsData = $this->chatRepository->getDataByIds($chatIds);

        return JsonResource::collection($chatsData);
    }

    public function show(Chat $chat): JsonResource
    {
        if (Gate::denies('view', [Chat::class, $chat])) {
            return new JsonResource([]);
        }

        $chat->load('data');

        $membersIds = $chat->membersIds();
        $chat->members = $this->userRepository->getByIds($membersIds);

        return new JsonResource($chat);
    }

    public function create(CreateChatDTO $createChatDTO, array $memberIds): void
    {
        Gate::authorize('create', [Chat::class, $createChatDTO]);

        $newChat = $this->chatRepository->create($createChatDTO);

        if ($createChatDTO->isTeamChat()) {
            $teamMemberIds = $this->teamMemberRepository->getByTeamId($createChatDTO->adminId)
                ->pluck('user_id')->toArray();

            $this->chatMemberRepository->addMembersToChat($newChat->id, $teamMemberIds);
        } elseif ($createChatDTO->isUserGroupChat()) {
            $this->chatMemberRepository->addMembersToChat($newChat->id, $memberIds);
        }
    }

    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void
    {
        Gate::authorize('update', [Chat::class, $updateChatDTO]);

        $this->chatRepository->update($chat, $updateChatDTO);
    }
}
