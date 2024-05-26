<?php

namespace App\Services\Post;

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

    public function create(int $postCommentId): void
    {
        $this->postCommentLikeRepository->create($postCommentId, $this->authorizedUserId);
    }

    public function delete(int $postCommentId): void
    {
        $this->postCommentLikeRepository->delete($postCommentId, $this->authorizedUserId);
    }
}
