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
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Traits\CreateDTO;
use App\Traits\SetAdditionalData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PostCommentService implements PostCommentServiceInterface
{
    use SetAdditionalData;

    protected $userRepository;
    protected $postCommentRepository;
    protected $postCommentLikeRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PostCommentRepositoryInterface $postCommentRepository,
        PostCommentLikeRepositoryInterface $postCommentLikeRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->postCommentLikeRepository = $postCommentLikeRepository;
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
        $this->setCommentsRights($postComments);
        $this->setLikeAbility($postComments);

        return $postComments;
    }

    protected function setCommentsRights(Collection &$postComments): void
    {
        foreach ($postComments as $postComment) {
            $postComment->canDelete = Gate::allows(DELETE_POST_COMMENT_GATE, [PostComment::class, $postComment]);
        }
    }

    protected function setLikeAbility(Collection &$postComments): void
    {
        $postCommentsIds = $postComments->pluck('id')->toArray();
        $postCommentsLikes = $this->postCommentLikeRepository->getByUserAndCommentsIds($this->authorizedUserId, $postCommentsIds);

        $likesKeyedByPostCommentId = $postCommentsLikes->keyBy('post_comment_id');

        foreach ($postComments as $postComment) {
            $postComment->isLiked = isset($likesKeyedByPostCommentId[$postComment->id]);
        }
    }
}
