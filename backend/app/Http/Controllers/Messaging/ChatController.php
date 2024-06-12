<?php

namespace App\Http\Controllers\Messaging;

use App\DTO\Message\CreateChatDTO;
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
        /** @var CreateChatDTO */
        $createChatDTO = $request->createDTO();
        $createChatDTO->setChatId(0)->setAdminId($team->admin_id);

        return $this->handleServiceCall(function () use ($team, $createChatDTO) {
            return $this->chatService->create($team->id, $createChatDTO);
        });
    }

    public function update(int $chatId, UpdateChatRequest $request)
    {
        $updateChatDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($chatId, $updateChatDTO) {
            return $this->chatService->update($chatId, $updateChatDTO);
        });
    }
}
