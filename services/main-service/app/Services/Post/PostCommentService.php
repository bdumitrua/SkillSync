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
use Illuminate\Support\Facades\Auth;

class PostCommentService implements PostCommentServiceInterface
{
    use CreateDTO;

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

    public function create(int $postId, CreatePostCommentRequest $request): void
    {
        /** @var CreatePostCommentDTO */
        $createPostCommentDTO = $this->createDTO($request, CreatePostCommentDTO::class);
        $createPostCommentDTO->postId = $postId;
        $createPostCommentDTO->userId = $this->authorizedUserId;

        $this->postCommentRepository->create($createPostCommentDTO);
    }

    public function delete(PostComment $postComment): void
    {
        // TODO GATE: Check if authorized user is author
        $this->postCommentRepository->delete($postComment);
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
