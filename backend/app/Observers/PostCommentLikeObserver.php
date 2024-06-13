<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Repositories\Post\PostCommentRepository;
use App\Models\PostCommentLike;
use App\Jobs\NotifyAboutCommentLike;

class PostCommentLikeObserver
{
    protected $postCommentRepository;

    public function __construct(PostCommentRepository $postCommentRepository)
    {
        $this->postCommentRepository = $postCommentRepository;
    }

    /**
     * Handle the PostCommentLike "created" event.
     */
    public function created(PostCommentLike $postCommentLike): void
    {
        Log::debug("Handling 'created' method in PostCommentLikeObserver", [
            'postCommentLike' => $postCommentLike->toArray()
        ]);

        $postComment = $this->postCommentRepository->getById($postCommentLike->post_comment_id);

        NotifyAboutCommentLike::dispatch($postComment, $postCommentLike);
    }
}
