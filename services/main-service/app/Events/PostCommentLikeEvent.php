<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use App\Models\PostCommentLike;

class PostCommentLikeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PostCommentLike $postCommentLike;
    /**
     * Create a new event instance.
     */
    public function __construct(PostCommentLike $postCommentLike)
    {
        $this->postCommentLike = $postCommentLike;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
