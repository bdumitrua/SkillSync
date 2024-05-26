<?php

namespace App\Repositories\Post;

use App\Models\PostCommentLike;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class PostCommentLikeRepository implements PostCommentLikeRepositoryInterface
{
    protected function queryByBothIds(int $postCommentId, int $userId): Builder
    {
        return PostCommentLike::query()
            ->where('post_comment_id', '=', $postCommentId)
            ->where('user_id', '=', $userId);
    }

    public function getByBothIds(int $postCommentId, int $userId): ?PostCommentLike
    {
        return $this->queryByBothIds($postCommentId, $userId)->first();
    }

    public function userLikedComment(int $userId, int $postCommentId): bool
    {
        return $this->queryByBothIds($postCommentId, $userId)->exists();
    }

    public function create(int $postCommentId, int $userId): bool
    {
        if ($this->userLikedComment($userId, $postCommentId)) {
            return false;
        }

        PostCommentLike::create([
            'post_comment_id' => $postCommentId,
            'user_id' => $userId
        ]);

        return true;
    }

    public function delete(int $postCommentId, int $userId): bool
    {
        $like = $this->getByBothIds($postCommentId, $userId);

        if (empty($like)) {
            return false;
        }

        return $like->delete();
    }
}
