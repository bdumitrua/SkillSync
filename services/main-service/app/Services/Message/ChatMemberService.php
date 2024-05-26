<?php

namespace App\Services\Message;

use App\Repositories\Message\Interfaces\ChatMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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

    public function show(int $chatId): JsonResource
    {
        return new JsonResource(
            $this->chatMemberRepository->getByChatId($chatId)
        );
    }

    public function add(int $chatId, int $userId): void
    {
        $this->authorizeModerator($chatId);
        // 
    }

    public function remove(int $chatId): void
    {
        $this->authorizeModerator($chatId);
        // 
    }

    // TODO move somewhere
    protected function authorizeModerator(int $chatId): void
    {
        if (empty($team = $this->teamRepository->getByChatId($chatId))) {
            return;
        }

        Gate::authorize('moderator', $team->id);
    }
}
