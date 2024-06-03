<?php

namespace App\Repositories\Post;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;

class PostRepository implements PostRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getAll(): Collection
    {
        return Post::get();
    }

    public function feed(int $userId): Collection
    {
        // TODO QUERY
        return Post::get();
    }

    public function getById(int $postId): ?Post
    {
        $cacheKey = $this->getPostCacheKey($postId);
        return $this->getCachedData($cacheKey, CACHE_TIME_POST_DATA, function () use ($postId) {
            return Post::where('id', '=', $postId)->first();
        });
    }

    public function getByIds(array $postIds): Collection
    {
        return $this->getCachedCollection($postIds, function ($postId) {
            return $this->getById($postId);
        });
    }

    public function getByUserId(int $userId): Collection
    {
        return Post::where('entity_type', '=', 'user')->where('entity_id', '=', $userId)->get();
    }

    public function getByTeamId(int $teamId): Collection
    {
        return Post::where('entity_type', '=', 'team')->where('entity_id', '=', $teamId)->get();
    }

    public function create(CreatePostDTO $dto): Post
    {
        $newPost = Post::create(
            $dto->toArray()
        );

        return $newPost;
    }

    public function update(Post $post, UpdatePostDTO $dto): void
    {
        $this->updateFromDto($post, $dto);
        $this->clearPostCache($post->id);
    }

    public function delete(Post $post): void
    {
        $postId = $post->id;

        $post->delete();
        $this->clearPostCache($postId);
    }

    protected function getPostCacheKey(int $postId): string
    {
        return CACHE_KEY_POST_DATA . $postId;
    }

    protected function clearPostCache(int $postId): void
    {
        $this->clearCache($this->getPostCacheKey($postId));
    }
}
