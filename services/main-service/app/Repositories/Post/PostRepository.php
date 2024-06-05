<?php

namespace App\Repositories\Post;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Models\Team;
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

    public function search(string $query): Collection
    {
        return Post::search($query);
    }

    public function getByUserId(int $userId): Collection
    {
        Log::debug("Getting posts by userId", [
            'userId' => $userId
        ]);

        $cacheKey = $this->getUserPostsCacheKey($userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_POST_DATA, function () use ($userId) {
            return Post::where('entity_type', '=', 'user')->where('entity_id', '=', $userId)->get();
        });
    }

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug("Getting posts by teamId", [
            'teamId' => $teamId
        ]);

        $cacheKey = $this->getTeamPostsCacheKey($teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_POST_DATA, function () use ($teamId) {
            return Post::where('entity_type', '=', 'team')->where('entity_id', '=', $teamId)->get();
        });
    }

    public function create(CreatePostDTO $dto): Post
    {
        Log::debug('Creating post from dto', [
            'dto' => $dto->toArray()
        ]);

        $newPost = Post::create(
            $dto->toArray()
        );

        $this->clearEntityPostsCache($newPost->entity_type, $newPost->entity_id);

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
        $this->clearEntityPostsCache($post->entity_type, $post->entity_id);
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
        $this->clearEntityPostsCache($postData['entity_type'], $postData['entity_id']);

        Log::debug('Succesfully deleted post', [
            'postData' => $postData,
        ]);
    }

    protected function getUserPostsCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_POST_DATA . $userId;
    }

    protected function getTeamPostsCacheKey(int $teamId): string
    {
        return CACHE_KEY_TEAM_POST_DATA . $teamId;
    }

    protected function getPostCacheKey(int $postId): string
    {
        return CACHE_KEY_POST_DATA . $postId;
    }

    protected function clearEntityPostsCache(string $entityType, int $entityId): void
    {
        if ($entityType === config('entities.user')) {
            $this->clearCache($this->getUserPostsCacheKey($entityId));
        } elseif ($entityType === config('entities.team')) {
            $this->clearCache($this->getTeamPostsCacheKey($entityId));
        }
    }

    protected function clearPostCache(int $postId): void
    {
        $this->clearCache($this->getPostCacheKey($postId));
    }
}
