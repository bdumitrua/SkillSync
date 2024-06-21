<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\User;
use App\Models\GroupChat;
use App\Models\DialogChat;
use App\Models\Chat;
use App\Http\Resources\Message\ChatResource;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Http\Requests\Message\CreateChatRequest;
use App\Enums\ChatType;
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
        $chatIds = $this->chatMemberRepository->getIdsByUserId($this->authorizedUserId);
        $chatsData = $this->chatRepository->getDataByIds($chatIds);
        $this->setDialogsMembersData($chatsData);

        return ChatResource::collection($chatsData);
    }

    public function search(string $query): JsonResource
    {
        $chats = $this->chatRepository->search($query);

        return ChatResource::collection($chats);
    }

    public function show(Chat $chat): JsonResource
    {
        if (Gate::denies('view', [Chat::class, $chat])) {
            return new JsonResource([]);
        }

        $chat->data = $this->chatRepository->getChatData($chat);

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
        Gate::authorize('update', [Chat::class, $chat]);

        $this->chatRepository->update($chat, $updateChatDTO);
    }

    protected function setDialogsMembersData(Collection &$chats): void
    {
        foreach ($chats as $chat) {
            if ($chat->type === ChatType::Dialog->value) {
                $chat->data->membersData = new Collection(
                    array_map(function ($memberData) {
                        return new User($memberData);
                    }, $chat->data->toSearchableArray()['membersData'])
                );
            }
        }
    }
}
