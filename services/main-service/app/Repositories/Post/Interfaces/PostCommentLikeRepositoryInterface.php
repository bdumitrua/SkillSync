<?php

namespace App\Repositories\Post\Interfaces;

use App\Models\PostCommentLike;

interface PostCommentLikeRepositoryInterface
{
    /**
     * @param int $postCommentId
     * @param int $userId
     * 
     * @return PostCommentLike|null
     */
    public function getByBothIds(int $postCommentId, int $userId): ?PostCommentLike;
}
