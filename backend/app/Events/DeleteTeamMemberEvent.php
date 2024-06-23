<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;

class DeleteTeamMemberEvent
{
    use Dispatchable, SerializesModels;

    public $teamId;
    public $memberId;

    public function __construct($teamId, $memberId)
    {
        $this->teamId = $teamId;
        $this->memberId = $memberId;
    }
}
