<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use Illuminate\Http\Request;

class ChatMemberController extends Controller
{
    private $chatMemberService;

    public function __construct(ChatMemberServiceInterface $chatMemberService)
    {
        $this->chatMemberService = $chatMemberService;
    }

    public function show(int $chatId)
    {
        return $this->handleServiceCall(function () use ($chatId) {
            return $this->chatMemberService->show($chatId);
        });
    }

    public function add(int $chatId, int $userId)
    {
        return $this->handleServiceCall(function () use ($chatId, $userId) {
            return $this->chatMemberService->add($chatId, $userId);
        });
    }

    public function remove(int $chatId, int $userId)
    {
        return $this->handleServiceCall(function () use ($chatId, $userId) {
            return $this->chatMemberService->remove($chatId, $userId);
        });
    }
}
