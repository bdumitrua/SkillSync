<?php

namespace App\Services\Message;

use App\Http\Requests\Message\CreateChatRequest;
use App\Http\Requests\Message\UpdateChatRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatService implements ChatServiceInterface
{
    protected $chatRepository;
    protected ?int $authorizedUserId;

    public function __construct(ChatRepositoryInterface $chatRepository)
    {
        $this->chatRepository = $chatRepository;
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
