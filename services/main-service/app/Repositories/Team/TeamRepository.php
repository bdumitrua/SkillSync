<?php

namespace App\Repositories\Team;

use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface
{
    public function getAll(): Collection
    {
        return new Collection();
    }

    public function getById(int $teamId): ?Team
    {
        return null;
    }

    public function getByUserId(int $userId): Collection
    {
        return new Collection();
    }

    public function getByIds(array $teamIds): Collection
    {
        return new Collection();
    }

    public function create(Team $team): Team
    {
        return new Team();
    }

    public function update(Team $team, array $data): ?Team
    {
        return null;
    }

    public function delete(Team $team): ?Team
    {
        return null;
    }
}
