<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TagRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param array $userIds
     * 
     * @return Collection
     */
    public function getByUserIds(array $userIds): Collection;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param array $teamIds
     * 
     * @return Collection
     */
    public function getByTeamIds(array $teamIds): Collection;

    /**
     * @param int $postId
     * 
     * @return Collection
     */
    public function getByPostId(int $postId): Collection;

    /**
     * @param array $postIds
     * 
     * @return Collection
     */
    public function getByPostIds(array $postIds): Collection;
}
