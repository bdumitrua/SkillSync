<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Models\User;
use App\Models\Chat;

class ChatMemberService implements ChatMemberServiceInterface
{
    protected $teamRepository;
    protected $chatMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TeamRepositoryInterface $teamRepository,
        ChatMemberRepositoryInterface $chatMemberRepository,
    ) {
        $this->teamRepository = $teamRepository;
        $this->chatMemberRepository = $chatMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function show(Chat $chat): JsonResource
    {
        return new JsonResource([]);
    }

    public function add(Chat $chat, User $user): void
    {
        // 
    }

    public function remove(Chat $chat, User $user): void
    {
        // 
    }
}
