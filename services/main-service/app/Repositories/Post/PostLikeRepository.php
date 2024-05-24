<?php

namespace App\Repositories\Post;

use App\Models\PostLike;
use App\Repositories\Post\Interfaces\PostLikeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PostLikeRepository implements PostLikeRepositoryInterface
{
    public function getByUserId(int $userId): Collection
    {
        return PostLike::where("user_id", '=', $userId)->get();
    }

    public function getByPostId(int $postId): Collection
    {
        return PostLike::where("post_id", '=', $postId)->get();
    }

    public function getByBothIds(int $postId, int $userId): Collection
    {
        return PostLike::where('post_id', '=', $postId)->where("user_id", '=', $userId)->get();
    }
}
