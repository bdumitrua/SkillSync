<?php

namespace App\Services\Post;

use App\Http\Resources\Post\PostCommentResource;
use App\Models\PostComment;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentService implements PostCommentServiceInterface
{
    protected $userRepository;
    protected $postCommentRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->postCommentRepository = $postCommentRepository;
    }

    public function post(int $postId): JsonResource
    {
        $postComments = $this->postCommentRepository->getByPostId($postId);
        $postComments = $this->assembleCommentsData($postComments);

        return PostCommentResource::collection($postComments);
    }

    public function create(int $postId): void
    {
        //
    }

    public function delete(PostComment $postComment): void
    {
        //
    }

    protected function assembleCommentsData(Collection $postComments): Collection
    {
        $userIds = $postComments->pluck('user_id')->unique()->all();
        $usersData = $this->userRepository->getByIds($userIds);

        foreach ($postComments as $comment) {
            $comment->userData = $usersData->where('id', $comment->user_id)->first();
        }

        return $postComments;
    }
}
