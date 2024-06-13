<?php

namespace App\Observers;

use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\PostLike;
use App\Events\PostLikeEvent;

class PostLikeObserver
{
    protected $postRepository;
    protected $userRepository;
    protected $teamRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
    }

    /**
     * Handle the PostLike "created" event.
     */
    public function created(PostLike $postLike): void
    {
        event(new PostLikeEvent($postLike));
    }
}
