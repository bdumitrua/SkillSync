<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;

class MessageSentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chatId;
    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(int $chatId, $message)
    {
        $this->chatId = $chatId;
        $this->message = $message;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('chat.' . $this->chatId);
    }

    public function broadcastQueue(): string
    {
        return 'websockets';
    }
}
