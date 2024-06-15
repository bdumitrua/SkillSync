<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\PostComment;
use App\Models\Post;
use App\Models\Like;
use App\Jobs\NotifyAboutPostLike;
use App\Jobs\NotifyAboutCommentLike;

class LikeObserver
{
    protected $likeRepository;

    public function __construct(
        LikeRepositoryInterface $likeRepository,
    ) {
        $this->likeRepository = $likeRepository;
    }

    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        Log::debug("Handling 'created' method in LikeObserver", [
            'like' => $like->toArray()
        ]);

        /** @var Post|PostComment|null */
        $likeableModel = $like->likeable()->first();

        if ($likeableModel instanceof Post) {
            NotifyAboutPostLike::dispatch($likeableModel, $like);
        } elseif ($likeableModel instanceof PostComment) {
            NotifyAboutCommentLike::dispatch($likeableModel, $like);
        }
    }
}
