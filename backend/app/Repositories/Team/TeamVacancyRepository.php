<?php

namespace App\Repositories\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;

class TeamVacancyRepository implements TeamVacancyRepositoryInterface
{
    use Updateable, Cacheable;

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug('Getting vacancies by teamId', [
            'teamId' => $teamId
        ]);

        $cacheKey = $this->getTeamVacanciesCacheKey($teamId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_VACANCIES_DATA, function () use ($teamId) {
            return TeamVacancy::where('team_id', '=', $teamId)->get();
        });
    }

    public function getByIds(array $teamVacancyIds): Collection
    {
        Log::debug('Getting vacancies data by ids', [
            'teamVacancyIds' => $teamVacancyIds
        ]);

        $vacancies = $this->getCachedCollection($teamVacancyIds, function ($id) {
            return $this->getById($id);
        });

        return $vacancies->sortByDesc('created_at')->values();
    }

    public function getById(int $teamVacancyId): ?TeamVacancy
    {
        Log::debug('Getting vacancy data by id', [
            'teamVacancyId' => $teamVacancyId
        ]);

        $cacheKey = $this->getVacancyCacheKey($teamVacancyId);
        return $this->getCachedData($cacheKey, CACHE_TIME_TEAM_VACANCY_DATA, function () use ($teamVacancyId) {
            return TeamVacancy::find($teamVacancyId);
        });
    }

    public function create(CreateTeamVacancyDTO $dto): TeamVacancy
    {
        Log::debug('Creating teamVacancy from dto', [
            'dto' => $dto->toArray()
        ]);

        $newVacancy = TeamVacancy::create(
            $dto->toArray()
        );

        Log::debug('Succesfully created teamVacancy from dto', [
            'dto' => $dto->toArray(),
            'newVacancy' => $newVacancy->toArray()
        ]);

        return $newVacancy;
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $dto): void
    {
        Log::debug('Updating teamVacancy from dto', [
            'teamVacancy id' => $teamVacancy->id,
            'dto' => $dto->toArray()
        ]);

        $this->updateFromDto($teamVacancy, $dto);

        Log::debug('Succesfully updated teamVacancy from dto', [
            'teamVacancy id' => $teamVacancy->id,
        ]);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        $teamVacancyData = $teamVacancy->toArray();

        Log::debug('Deleting teamVacancy', [
            'teamVacancyData' => $teamVacancyData,
            'authorizedUserId' => Auth::id()
        ]);

        $teamVacancy->delete();

        Log::debug('Succesfully deleted teamVacancy', [
            'teamVacancyData' => $teamVacancyData,
        ]);
    }

    protected function getVacancyCacheKey(int $teamVacancyId): string
    {
        return CACHE_KEY_VACANCY_DATA . $teamVacancyId;
    }

    protected function getTeamVacanciesCacheKey(int $teamId): string
    {
        return CACHE_KEY_TEAM_VACANCIES_DATA . $teamId;
    }
}
