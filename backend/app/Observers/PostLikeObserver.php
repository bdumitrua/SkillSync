<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\PostLike;
use App\Jobs\NotifyAboutPostLike;

class PostLikeObserver
{
    protected $postRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
    ) {
        $this->postRepository = $postRepository;
    }

    /**
     * Handle the PostLike "created" event.
     */
    public function created(PostLike $postLike): void
    {
        Log::debug("Handling 'created' method in PostLikeObserver", [
            'postLike' => $postLike->toArray()
        ]);

        $post = $this->postRepository->getById($postLike->post_id);

        NotifyAboutPostLike::dispatch($post, $postLike);
    }
}
