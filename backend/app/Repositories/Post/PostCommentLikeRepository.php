<?php

namespace App\Repositories\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;
use App\Models\PostCommentLike;

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
        Log::debug("Getting postComment like by both ids", [
            'postCommentId' => $postCommentId,
            'userId' => $userId
        ]);

        return $this->queryByBothIds($postCommentId, $userId)->first();
    }

    public function getByUserAndCommentsIds(int $userId, array $postCommentsIds): Collection
    {
        return PostCommentLike::where('user_id', '=', $userId)->whereIn('post_comment_id', $postCommentsIds)->get();
    }

    public function userLikedComment(int $userId, int $postCommentId): bool
    {
        Log::debug("Checking if user liked comment", [
            'userId' => $userId,
            'postCommentId' => $postCommentId
        ]);

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
