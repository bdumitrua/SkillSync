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
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;
}
