<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use App\Models\Notification;

class NewNotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Notification $notification;
    public $from;
    public $to;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
        $this->notification->from = $notification->from;
        $this->notification->to = $notification->to;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('user.notifications.' . $this->notification->receiver_id);
    }

    public function broadcastQueue(): string
    {
        return 'websockets';
    }

    public function broadcastWith()
    {
        return [
            'notification' => $this->notification,
        ];
    }
}
