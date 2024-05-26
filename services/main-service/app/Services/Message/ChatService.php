<?php

namespace App\Services\Message;

use App\Http\Requests\Message\CreateChatRequest;
use App\Http\Requests\Message\UpdateChatRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ChatService implements ChatServiceInterface
{
    protected $chatRepository;
    protected $teamRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        TeamRepositoryInterface $teamRepository,
    ) {
        $this->chatRepository = $chatRepository;
        $this->teamRepository = $teamRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return JsonResource::collection(
            $this->chatRepository->getByUserId($this->authorizedUserId)
        );
    }

    public function show(int $chatId): JsonResource
    {
        // TODO check if authorized user is chat member
        return new JsonResource(
            $this->chatRepository->getById($chatId)
        );
    }

    public function create(int $teamId, CreateChatRequest $request): void
    {
        // 
    }

    public function update(int $chatId, UpdateChatRequest $request): void
    {
        // 
    }
}
