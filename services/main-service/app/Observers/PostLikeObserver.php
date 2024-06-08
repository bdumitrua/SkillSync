<?php

namespace App\Observers;

use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\PostLike;
use App\Kafka\KafkaService;
use App\Events\PostLikeEvent;

class PostLikeObserver
{
    protected $kafkaService;
    protected $postRepository;
    protected $userRepository;
    protected $teamRepository;

    public function __construct(
        KafkaService $kafkaService,
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
    ) {
        $this->kafkaService = $kafkaService;
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
        $post = $this->postRepository->getById($postLike->post_id);
        $whoLiked = $this->userRepository->getById($postLike->user_id);
        $postAuthor = [];

        if ($post->entity_type === config('entities.user')) {
            $postAuthor['type'] = 'user';
            $postAuthor['data'] = $this->userRepository->getById($post->entity_id);
        } elseif ($post->entity_type === config('entities.team')) {
            $postAuthor['type'] = 'team';
            $postAuthor['data'] = $this->teamRepository->getById($post->entity_id);
        }

        $this->kafkaService->sendPostLike(
            $post->toArray(),
            $postAuthor,
            $whoLiked->toArray()
        );
    }
}
