<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Events\DeleteTeamMemberEvent;

class DeleteTeamMemberListener
{
    protected $chatRepository;
    protected $chatMemberService;

    public function __construct(
        ChatRepositoryInterface $chatRepository,
        ChatMemberServiceInterface $chatMemberService,
    ) {
        $this->chatRepository = $chatRepository;
        $this->chatMemberService = $chatMemberService;
    }

    /**
     * Handle the event.
     */
    public function handle(DeleteTeamMemberEvent $event): void
    {
        try {
            $teamChat = $this->chatRepository->getChatByTeamId($event->teamId);

            if (!empty($teamChat)) {
                $this->chatMemberService->delete($teamChat, $event->memberId);
            }
        } catch (\Exception $e) {
            Log::error('Error removing teamMember from teamChat', [
                'teamChat' => $teamChat?->toArray(),
                'memberId' => $event->memberId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
