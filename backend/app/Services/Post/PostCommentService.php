<?php

namespace App\Services\Post;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Post\Interfaces\PostCommentServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\PostComment;
use App\Http\Resources\Post\PostCommentResource;
use App\DTO\Post\CreatePostCommentDTO;

class PostCommentService implements PostCommentServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $postCommentRepository;
    protected $likeRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        LikeRepositoryInterface $likeRepository,
        PostCommentRepositoryInterface $postCommentRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
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
        Gate::authorize('delete', [PostComment::class, $postComment]);

        $this->postCommentRepository->delete($postComment);
    }

    protected function assembleCommentsData(Collection $postComments): Collection
    {
        $this->setCollectionEntityData($postComments, 'user_id', 'author', $this->userRepository);
        $this->setCommentsRights($postComments);
        $this->setCollectionIsLiked($postComments, 'postComment', $this->likeRepository);

        return $postComments;
    }

    protected function setCommentsRights(Collection &$postComments): void
    {
        foreach ($postComments as $postComment) {
            $postComment->canDelete = Gate::allows('delete', [PostComment::class, $postComment]);
        }
    }
}
