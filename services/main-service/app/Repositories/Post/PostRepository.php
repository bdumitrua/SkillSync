<?php

namespace App\Repositories\Post;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\DTO\Team\UpdateTeamDTO;
use App\DTO\Post\CreatePostDTO;
use App\Traits\UpdateFromDTO;

class PostRepository implements PostRepositoryInterface
{
    use UpdateFromDTO;

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

    public function create(CreatePostDTO $dto): Post
    {
        $newPost = Post::create(
            $dto->toArray()
        );

        return $newPost;
    }

    public function update(Post $post, UpdateTeamDTO $dto): void
    {
        $this->updateFromDto($post, $dto);
    }

    public function delete(Post $post): void
    {
        $post->delete();
    }
}
