<?php

namespace App\Repositories\Post;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;
use Illuminate\Support\Facades\Log;

class PostRepository implements PostRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getAll(): Collection
    {
        return Post::get();
    }

    public function feed(int $userId): Collection
    {
        Log::debug("Getting user feed", [
            'userId' => $userId
        ]);

        // TODO QUERY
        return Post::get();
    }

    public function getById(int $postId): ?Post
    {
        Log::debug("Getting post by id", [
            'postId' => $postId
        ]);

        $cacheKey = $this->getPostCacheKey($postId);
        return $this->getCachedData($cacheKey, CACHE_TIME_POST_DATA, function () use ($postId) {
            return Post::where('id', '=', $postId)->first();
        });
    }

    public function getByIds(array $postIds): Collection
    {
        Log::debug("Getting posts by ids", [
            'postIds' => $postIds
        ]);

        return $this->getCachedCollection($postIds, function ($postId) {
            return $this->getById($postId);
        });
    }

    // TODO CACHE
    public function getByUserId(int $userId): Collection
    {
        Log::debug("Getting posts by userId", [
            'userId' => $userId
        ]);

        return Post::where('entity_type', '=', 'user')->where('entity_id', '=', $userId)->get();
    }

    // TODO CACHE
    public function getByTeamId(int $teamId): Collection
    {
        Log::debug("Getting posts by teamId", [
            'teamId' => $teamId
        ]);

        return Post::where('entity_type', '=', 'team')->where('entity_id', '=', $teamId)->get();
    }

    // TODO RE-CACHE
    public function create(CreatePostDTO $dto): Post
    {
        Log::debug('Creating post from dto', [
            'dto' => $dto->toArray()
        ]);

        $newPost = Post::create(
            $dto->toArray()
        );

        Log::debug('Succesfully created post from dto', [
            'dto' => $dto->toArray(),
            'newPost' => $newPost->toArray()
        ]);

        return $newPost;
    }

    public function update(Post $post, UpdatePostDTO $dto): void
    {
        Log::debug('updating post data from dto', [
            'dto' => $dto
        ]);

        $this->updateFromDto($post, $dto);
        $this->clearPostCache($post->id);
    }

    public function delete(Post $post): void
    {
        $postId = $post->id;
        $postData = $post->toArray();

        Log::debug('Deleting post', [
            'postData' => $postData,
        ]);

        $post->delete();
        $this->clearPostCache($postId);

        Log::debug('Succesfully deleted post', [
            'postData' => $postData,
        ]);
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
