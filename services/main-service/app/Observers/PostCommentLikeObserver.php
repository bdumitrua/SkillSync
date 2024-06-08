<?php

namespace App\Observers;

use App\Models\PostCommentLike;
use App\Events\PostCommentLikeEvent;

class PostCommentLikeObserver
{
    /**
     * Handle the PostCommentLike "created" event.
     */
    public function created(PostCommentLike $postCommentLike): void
    {
        event(new PostCommentLikeEvent($postCommentLike));
    }
}
