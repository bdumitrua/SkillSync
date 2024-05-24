<?php

namespace App\Repositories\Post;

use App\Models\PostCommentLike;
use App\Repositories\Post\Interfaces\PostCommentLikeRepositoryInterface;

class PostCommentLikeRepository implements PostCommentLikeRepositoryInterface
{
    public function getByBothIds(int $postCommentId, int $userId): ?PostCommentLike
    {
        return PostCommentLike::where('post_comment_id', '=', $postCommentId)
            ->where('user_id', '=', $userId)
            ->get();
    }
}
