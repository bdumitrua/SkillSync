<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use App\Models\PostLike;

class PostLikeEvent implements ShouldBroadCast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PostLike $postLike;

    /**
     * Create a new event instance.
     */
    public function __construct(PostLike $postLike)
    {
        $this->postLike = $postLike;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('post-likes-channel');
    }

    public function broadcastWith()
    {
        return ['postLike' => $this->postLike];
    }

    /**
     * Get the broadcast queue for the event.
     */
    public function broadcastQueue(): string
    {
        return 'websockets';
    }
}
