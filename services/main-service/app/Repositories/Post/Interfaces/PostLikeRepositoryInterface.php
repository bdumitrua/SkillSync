<?php

namespace App\Repositories\Post\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface PostLikeRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param int $postId
     * 
     * @return Collection
     */
    public function getByPostId(int $postId): Collection;

    /**
     * @param int $postId
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByBothIds(int $postId, int $userId): Collection;
}
