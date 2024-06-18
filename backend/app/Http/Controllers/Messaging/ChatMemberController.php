<?php

namespace App\Http\Controllers\Messaging;

use Illuminate\Http\Request;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Models\User;
use App\Models\Chat;
use App\Http\Controllers\Controller;

class ChatMemberController extends Controller
{
    private $chatMemberService;

    public function __construct(ChatMemberServiceInterface $chatMemberService)
    {
        $this->chatMemberService = $chatMemberService;
    }

    public function show(Chat $chat)
    {
        return $this->handleServiceCall(function () use ($chat) {
            return $this->chatMemberService->show($chat);
        });
    }

    public function add(Chat $chat, User $user)
    {
        return $this->handleServiceCall(function () use ($chat, $user) {
            return $this->chatMemberService->add($chat, $user);
        });
    }

    public function remove(Chat $chat, User $user)
    {
        return $this->handleServiceCall(function () use ($chat, $user) {
            return $this->chatMemberService->remove($chat, $user);
        });
    }
}
