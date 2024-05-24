<?php

namespace App\Repositories\Interfaces;

use App\Models\Team;
use Illuminate\Support\Collection;

interface TeamRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $id
     * 
     * @return Team|null
     */
    public function getById(int $id): ?Team;

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
