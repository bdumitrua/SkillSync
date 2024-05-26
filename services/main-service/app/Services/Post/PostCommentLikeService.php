<?php

namespace App\Services\Post;

use App\Exceptions\LikeException;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;
use Illuminate\Support\Facades\Auth;

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
    public function create(int $postCommentId): void
    {
        $liked = $this->postCommentLikeRepository->create($postCommentId, $this->authorizedUserId);

        if (!$liked) {
            throw new LikeException("You already liked this comment.");
        }
    }

    /**
     * @throws LikeException
     */
    public function delete(int $postCommentId): void
    {
        $unliked = $this->postCommentLikeRepository->delete($postCommentId, $this->authorizedUserId);

        if (!$unliked) {
            throw new LikeException("You haven't liked this comment.");
        }
    }
}
