<?php

namespace App\Repositories\Post\Interfaces;

use App\DTO\Post\CreatePostDTO;
use App\DTO\Post\UpdatePostDTO;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function feed(int $userId): Collection;

    /**
     * @param int $postId
     * 
     * @return Post|null
     */
    public function getById(int $postId): ?Post;

    /**
     * @param array $postIds
     * 
     * @return Collection
     */
    public function getByIds(array $postIds): Collection;

    /**
     * @param string $query
     * 
     * @return Collection
     */
    public function search(string $query): Collection;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param CreatePostDTO $dto
     * 
     * @return Post
     */
    public function create(CreatePostDTO $dto): Post;

    /**
     * @param Post $post
     * @param UpdatePostDTO $dto
     * 
     * @return void
     */
    public function update(Post $post, UpdatePostDTO $dto): void;

    /**
     * @param Post $post
     * 
     * @return void
     */
    public function delete(Post $post): void;
}
