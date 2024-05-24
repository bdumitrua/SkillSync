<?php

namespace App\Repositories\Post;

use App\Models\PostComment;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PostCommentRepository implements PostCommentRepositoryInterface
{
    public function getById(int $postCommentId): ?PostComment
    {
        return PostComment::where('id', '=', $postCommentId)->first();
    }

    public function getByPostId(int $postId): Collection
    {
        return PostComment::where('post_id', '=', $postId)->get();
    }
}
