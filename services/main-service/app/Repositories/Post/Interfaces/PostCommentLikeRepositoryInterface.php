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

    /**
     * @param int $postCommentId
     * @param int $userId
     * 
     * @return bool
     */
    public function create(int $postCommentId, int $userId): bool;

    /**
     * @param int $postCommentId
     * @param int $userId
     * 
     * @return bool
     */
    public function delete(int $postCommentId, int $userId): bool;
}
