<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Models\Chat;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Http\Requests\Message\CreateChatRequest;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

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
        return new JsonResource([]);
    }

    public function show(Chat $chat): JsonResource
    {
        return new JsonResource([]);
    }

    public function create(CreateChatDTO $createChatDTO): void
    {
        // 
    }

    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void
    {
        // 
    }
}
