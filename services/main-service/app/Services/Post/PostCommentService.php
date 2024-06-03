<?php

namespace App\Services\Post;

use App\DTO\Post\CreatePostCommentDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Models\PostComment;
use App\Http\Resources\Post\PostCommentResource;
use App\Http\Requests\Post\CreatePostCommentRequest;
use App\Traits\CreateDTO;
use App\Traits\SetAdditionalData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostCommentService implements PostCommentServiceInterface
{
    use SetAdditionalData;

    protected $userRepository;
    protected $postCommentRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function post(int $postId): JsonResource
    {
        $postComments = $this->postCommentRepository->getByPostId($postId);
        $postComments = $this->assembleCommentsData($postComments);

        return PostCommentResource::collection($postComments);
    }

    public function create(int $postId, CreatePostCommentDTO $createPostCommentDTO): void
    {
        $this->postCommentRepository->create($createPostCommentDTO);
    }

    public function delete(PostComment $postComment): void
    {
        Gate::authorize(DELETE_POST_COMMENT_GATE, [PostComment::class, $postComment]);

        $this->postCommentRepository->delete($postComment);
    }

    protected function assembleCommentsData(Collection $postComments): Collection
    {
        $this->setCollectionEntityData($postComments, 'user_id', 'userData', $this->userRepository);

        return $postComments;
    }
}
