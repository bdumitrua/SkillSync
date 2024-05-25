<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\TeamApplication;
use App\DTO\Team\CreateTeamApplicationDTO;

class TeamApplicationRepository implements TeamApplicationRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        return TeamApplication::where('team_id', '=', $teamId)->get();
    }

    public function getByVacancyId(int $teamVacancyId): Collection
    {
        return TeamApplication::where('vacancy_id', '=', $teamVacancyId)->get();
    }

    public function getById(int $teamApplicationId): ?TeamApplication
    {
        return TeamApplication::find($teamApplicationId);
    }

    public function userAppliedToVacancy(int $userId, int $teamVacancyId): bool
    {
        return TeamApplication::where('user_id', '=', $userId)
            ->where('vacancy_id', '=', $teamVacancyId)
            ->exists();
    }

    public function create(CreateTeamApplicationDTO $dto): TeamApplication
    {
        $newApplication = TeamApplication::create(
            $dto->toArray()
        );

        return $newApplication;
    }

    public function update(TeamApplication $teamApplication, string $status): void
    {
        $teamApplication->update([
            'status' => $status
        ]);
    }

    public function delete(TeamApplication $teamApplication): void
    {
        $teamApplication->delete();
    }
}
