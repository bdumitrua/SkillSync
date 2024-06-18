<?php

namespace App\Http\Controllers\Messaging;

use Illuminate\Http\Request;
use App\Services\Message\Interfaces\ChatServiceInterface;
use App\Models\Team;
use App\Models\Chat;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Http\Requests\Message\CreateChatRequest;
use App\Http\Controllers\Controller;
use App\DTO\Message\CreateChatDTO;

class ChatController extends Controller
{
    private $chatService;

    public function __construct(ChatServiceInterface $chatService)
    {
        $this->chatService = $chatService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->chatService->index();
        });
    }

    public function show(Chat $chat)
    {
        return $this->handleServiceCall(function () use ($chat) {
            return $this->chatService->show($chat);
        });
    }

    public function create(CreateChatRequest $request)
    {
        /** @var CreateChatDTO */
        $createChatDTO = $request->createDTO();
        $memberIds = $request->input('memberIds') ?? [];

        return $this->handleServiceCall(function () use ($createChatDTO, $memberIds) {
            return $this->chatService->create($createChatDTO, $memberIds);
        });
    }

    public function update(Chat $chat, UpdateChatRequest $request)
    {
        $updateChatDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($chat, $updateChatDTO) {
            return $this->chatService->update($chat, $updateChatDTO);
        });
    }
}
