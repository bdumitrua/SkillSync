<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;
use App\Traits\UpdateFromDTO;

class TeamVacancyRepository implements TeamVacancyRepositoryInterface
{
    use UpdateFromDTO;

    public function getByTeamId(int $teamId): Collection
    {
        return TeamVacancy::where('team_id', '=', $teamId)->get();
    }

    public function getByIds(array $teamVacancyIds): Collection
    {
        return TeamVacancy::whereIn('id', $teamVacancyIds)->get();
    }

    public function getById(int $teamVacancyId): ?TeamVacancy
    {
        return TeamVacancy::find($teamVacancyId);
    }

    public function create(CreateTeamVacancyDTO $dto): TeamVacancy
    {
        $newVacancy = TeamVacancy::create(
            $dto->toArray()
        );

        return $newVacancy;
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $dto): void
    {
        $this->updateFromDto($teamVacancy, $dto);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        $teamVacancy->delete();
    }
}
