<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\CreateChatRequest;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Models\Team;
use App\Services\Message\Interfaces\ChatServiceInterface;
use Illuminate\Http\Request;

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

    public function show(int $chatId)
    {
        return $this->handleServiceCall(function () use ($chatId) {
            return $this->chatService->show($chatId);
        });
    }

    public function create(Team $team, CreateChatRequest $request)
    {
        return $this->handleServiceCall(function () use ($team, $request) {
            return $this->chatService->create($team->id, $request);
        });
    }

    public function update(int $chatId, UpdateChatRequest $request)
    {
        return $this->handleServiceCall(function () use ($chatId, $request) {
            return $this->chatService->update($chatId, $request);
        });
    }
}
