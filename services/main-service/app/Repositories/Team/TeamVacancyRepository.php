<?php

namespace App\Repositories\Team;

use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use Illuminate\Database\Eloquent\Collection;

class TeamVacancyRepository implements TeamVacancyRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        return new Collection();
    }

    public function getById(int $teamVacancyId): ?TeamVacancy
    {
        return null;
    }

    public function create(TeamVacancy $teamVacancy): TeamVacancy
    {
        return new TeamVacancy();
    }

    public function update(TeamVacancy $teamVacancy, array $data): TeamVacancy
    {
        return new TeamVacancy();
    }

    public function delete(TeamVacancy $teamVacancy): ?TeamVacancy
    {
        return null;
    }
}
