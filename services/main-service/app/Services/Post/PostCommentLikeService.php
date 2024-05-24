<?php

namespace App\Services\Post;

use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Services\Post\Interfaces\PostCommentLikeServiceInterface;

class PostCommentLikeService implements PostCommentLikeServiceInterface
{
    protected $postCommentLikeRepository;

    public function __construct(PostCommentLikeRepositoryInterface $postCommentLikeRepository)
    {
        $this->postCommentLikeRepository = $postCommentLikeRepository;
    }

    public function create(int $postCommentId): void
    {
        // 
    }

    public function delete(int $postCommentId): void
    {
        // 
    }
}
