<?php

namespace App\Repositories\Post\Interfaces;

use App\Models\PostCommentLike;
use Illuminate\Database\Eloquent\Collection;

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
     * @param int $userId
     * @param array $postCommentsIds
     * 
     * @return Collection
     */
    public function getByUserAndCommentsIds(int $userId, array $postCommentsIds): Collection;

    /**
     * @param int $userId
     * @param int $postCommentId
     * 
     * @return bool
     */
    public function userLikedComment(int $userId, int $postCommentId): bool;

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
