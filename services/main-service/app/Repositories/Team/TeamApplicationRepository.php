<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\TeamApplication;
use App\DTO\Team\CreateTeamApplicationDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamApplicationRepository implements TeamApplicationRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        Log::debug("Getting teamApplications by teamId", [
            'teamId' => $teamId
        ]);

        return TeamApplication::where('team_id', '=', $teamId)->get();
    }

    public function getByVacancyId(int $teamVacancyId): Collection
    {
        Log::debug("Getting teamApplications by teamVacancyId", [
            'teamVacancyId' => $teamVacancyId
        ]);

        return TeamApplication::where('vacancy_id', '=', $teamVacancyId)->get();
    }

    public function getById(int $teamApplicationId): ?TeamApplication
    {
        Log::debug("Getting teamApplication by id", [
            'teamApplicationId' => $teamApplicationId
        ]);

        return TeamApplication::find($teamApplicationId);
    }

    public function userAppliedToVacancy(int $userId, int $teamVacancyId): bool
    {
        Log::debug("Checking if user applied to vacancy", [
            'userId' => $userId,
            'teamVacancyId' => $teamVacancyId,
        ]);

        return TeamApplication::where('user_id', '=', $userId)
            ->where('vacancy_id', '=', $teamVacancyId)
            ->exists();
    }

    public function create(CreateTeamApplicationDTO $dto): TeamApplication
    {
        Log::debug('Creating teamApplication from dto', [
            'dto' => $dto->toArray()
        ]);

        $newApplication = TeamApplication::create(
            $dto->toArray()
        );

        Log::debug('Succesfully created teamApplication from dto', [
            'dto' => $dto->toArray(),
            'newApplication' => $newApplication->toArray()
        ]);

        return $newApplication;
    }

    public function update(TeamApplication $teamApplication, string $status): void
    {
        Log::debug('Updating teamApplication status', [
            'teamApplication id' => $teamApplication->id,
            'current status' => $teamApplication->status
        ]);
        $teamApplication->update([
            'status' => $status
        ]);

        Log::debug('Succesfully updated teamApplication status', [
            'teamApplication id' => $teamApplication->id,
            'new status' => $status
        ]);
    }

    public function delete(TeamApplication $teamApplication): void
    {
        $teamApplicationData = $teamApplication->toArray();
        Log::debug('Deleting teamApplication', [
            'teamApplicationData' => $teamApplicationData,
            'authorizedUserId' => Auth::id()
        ]);

        $teamApplication->delete();

        Log::debug('Succesfully deleted teamApplication', [
            'teamApplicationData' => $teamApplicationData,
        ]);
    }
}
