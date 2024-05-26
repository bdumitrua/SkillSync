<?php

namespace App\Services\Message;

use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ChatMemberService implements ChatMemberServiceInterface
{
    protected $chatMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(ChatMemberRepositoryInterface $chatMemberRepository)
    {
        $this->chatMemberRepository = $chatMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function show(int $chatId): JsonResource
    {
        return new JsonResource(
            $this->chatMemberRepository->getByChatId($chatId)
        );
    }

    public function add(int $chatId, int $userId): void
    {
        // TODO GATE: Check if authorized user is moderator
        // 
    }

    public function remove(int $chatId): void
    {
        // TODO GATE: Check if authorized user is moderator
        // 
    }
}
