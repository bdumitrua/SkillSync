<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Message\Interfaces\ChatMemberServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Message\Interfaces\ChatRepositoryInterface;
use App\Events\NewTeamMemberEvent;

class NewTeamMemberListener
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
    public function handle(NewTeamMemberEvent $event): void
    {
        try {
            $teamChat = $this->chatRepository->getChatByTeamId($event->teamId);

            if (!empty($teamChat)) {
                $this->chatMemberService->add($teamChat, $event->memberId);
            }
        } catch (\Exception $e) {
            Log::error('Error adding new teamMember to teamChat', [
                'teamChat' => $teamChat?->toArray(),
                'memberId' => $event->memberId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
