<?php

namespace App\Repositories\Team;

use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Models\TeamApplication;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TeamApplicationRepository implements TeamApplicationRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        return new Collection();
    }

    public function getByVacancyId(int $teamVacancyId): Collection
    {
        return new Collection();
    }

    public function getById(int $teamApplicationId): ?TeamApplication
    {
        return new TeamApplication();
    }

    public function create(TeamApplication $teamApplicationModel): ?TeamApplication
    {
        return new TeamApplication();
    }

    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $updateTeamApplicationDto): TeamApplication
    {
        return new TeamApplication();
    }

    public function delete(TeamApplication $teamApplication): ?TeamApplication
    {
        return null;
    }
}
