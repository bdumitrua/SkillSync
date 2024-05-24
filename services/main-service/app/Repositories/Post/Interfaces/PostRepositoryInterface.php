<?php

namespace App\Repositories\Post\Interfaces;

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
}
