<?php

namespace App\Repositories\Post\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\PostCommentLike;
use App\Models\PostComment;

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
     * @param PostComment $postComment
     * @param int $userId
     * 
     * @return bool
     */
    public function create(PostComment $postComment, int $userId): bool;

    /**
     * @param PostComment $postComment
     * @param int $userId
     * 
     * @return bool
     */
    public function delete(PostComment $postComment, int $userId): bool;
}
