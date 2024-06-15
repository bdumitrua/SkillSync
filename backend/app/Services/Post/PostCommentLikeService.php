<?php

namespace App\Services\Post;

use Illuminate\Support\Facades\Auth;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Models\PostComment;
use App\Exceptions\LikeException;

class PostCommentLikeService implements PostCommentLikeServiceInterface
{
    protected $postCommentLikeRepository;
    protected ?int $authorizedUserId;

    public function __construct(PostCommentLikeRepositoryInterface $postCommentLikeRepository)
    {
        $this->postCommentLikeRepository = $postCommentLikeRepository;
        $this->authorizedUserId = Auth::id();
    }

    /**
     * @throws LikeException
     */
    public function create(PostComment $postComment): void
    {
        $liked = $this->postCommentLikeRepository->create($postComment, $this->authorizedUserId);

        if (!$liked) {
            throw new LikeException("You already liked this comment.");
        }
    }

    /**
     * @throws LikeException
     */
    public function delete(PostComment $postComment): void
    {
        $unliked = $this->postCommentLikeRepository->delete($postComment, $this->authorizedUserId);

        if (!$unliked) {
            throw new LikeException("You haven't liked this comment.");
        }
    }
}
