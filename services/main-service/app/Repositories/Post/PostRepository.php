<?php

namespace App\Repositories\Post;

use App\Models\Post;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements PostRepositoryInterface
{
    public function getAll(): Collection
    {
        return Post::get();
    }

    public function feed(int $userId): Collection
    {
        // TODO 
        return Post::get();
    }

    public function getById(int $postId): ?Post
    {
        return Post::where('id', '=', $postId)->first();
    }

    public function getByIds(array $postIds): Collection
    {
        return Post::whereIn('id', $postIds)->get();
    }

    public function getByUserId(int $userId): Collection
    {
        return Post::where('entity_type', '=', 'user')->where('entity_id', '=', $userId)->get();
    }

    public function getByTeamId(int $teamId): Collection
    {
        return Post::where('entity_type', '=', 'team')->where('entity_id', '=', $teamId)->get();
    }
}
