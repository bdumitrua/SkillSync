<?php

namespace App\Repositories\Team\Interfaces;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $teamId
     * 
     * @return Team|null
     */
    public function getById(int $teamId): ?Team;

    /**
     * @param int $userId
     * 
     * @return Collection
     */
    public function getByUserId(int $userId): Collection;

    /**
     * @param array $teamIds
     * 
     * @return Collection
     */
    public function getByIds(array $teamIds): Collection;

    /**
     * @param Team $team
     * 
     * @return Team
     */
    public function create(Team $team): Team;

    /**
     * @param Team $team
     * @param array $data
     * 
     * @return Team|null
     */
    public function update(Team $team, array $data): ?Team;

    /**
     * @param Team $team
     * 
     * @return Team|null
     */
    public function delete(Team $team): ?Team;
}
