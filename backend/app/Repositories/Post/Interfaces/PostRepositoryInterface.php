<?php

namespace App\Repositories\Post\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IdentifiableRepositoryInterface;
use App\Models\Post;
use App\DTO\Post\UpdatePostDTO;
use App\DTO\Post\CreatePostDTO;

interface PostRepositoryInterface extends IdentifiableRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $userId
     * @param array $usersIds
     * @param array $teamsIds
     * 
     * @return Collection
     */
    public function feed(int $userId, array $usersIds, array $teamsIds): Collection;

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
