<?php

namespace App\Repositories\Post;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\DTO\Post\UpdatePostDTO;
use App\DTO\Post\CreatePostDTO;

class PostRepository implements PostRepositoryInterface
{
    use Updateable, Cacheable;

    public function getAll(): Collection
    {
        return Post::get();
    }

    public function feed(int $userId, array $usersIds, array $teamsIds): Collection
    {
        Log::debug("Getting user feed", [
            'userId' => $userId,
            'usersIds' => $usersIds,
            'teamsIds' => $teamsIds,
        ]);

        $query = Post::query();

        if (!empty($usersIds)) {
            $query->orWhere(function ($query) use ($usersIds) {
                $query->where('entity_type', '=', config('entities.user'))
                    ->whereIn('entity_id', $usersIds);
            });
        }

        if (!empty($teamsIds)) {
            $query->orWhere(function ($query) use ($teamsIds) {
                $query->where('entity_type', '=', config('entities.team'))
                    ->whereIn('entity_id', $teamsIds);
            });
        }

        // If user haven't subscribed to anyone - he will get 20 last posts
        // Not a but, but a feature
        return $query->latest()->take(20)->get();
    }

    public function getById(int $postId): ?Post
    {
        Log::debug("Getting post by id", [
            'postId' => $postId
        ]);

        return Post::where('id', '=', $postId)->first();
    }

    public function getByIds(array $postIds): Collection
    {
        Log::debug("Getting posts by ids", [
            'postIds' => $postIds
        ]);

        return Post::whereIn('id', $postIds)->get();
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

        return Post::where('entity_type', '=', config('entities.user'))->where('entity_id', '=', $userId)->get();
    }

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug("Getting posts by teamId", [
            'teamId' => $teamId
        ]);

        return Post::where('entity_type', '=', config('entities.team'))->where('entity_id', '=', $teamId)->get();
    }

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
    }

    public function delete(Post $post): void
    {
        $postData = $post->toArray();

        Log::debug('Deleting post', [
            'postData' => $postData,
        ]);

        $post->delete();

        Log::debug('Succesfully deleted post', [
            'postData' => $postData,
        ]);
    }
}
