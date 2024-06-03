<?php

namespace App\Repositories\Team;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TeamVacancyRepository implements TeamVacancyRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    public function getByTeamId(int $teamId): Collection
    {
        Log::debug('Getting vacancies by teamId', [
            'teamId' => $teamId
        ]);

        $teamVacancyIds = TeamVacancy::where('team_id', '=', $teamId)->get()
            ->pluck('id')->toArray();

        return $this->getByIds($teamVacancyIds);
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
            return  TeamVacancy::find($teamVacancyId);
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
        $this->clearVacancyCache($teamVacancy->id);

        Log::debug('Succesfully updated teamVacancy from dto', [
            'teamVacancy id' => $teamVacancy->id,
        ]);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        $teamVacancyId = $teamVacancy->id;
        $teamVacancyData = $teamVacancy->toArray();

        Log::debug('Deleting teamVacancy', [
            'teamVacancyData' => $teamVacancyData,
            'authorizedUserId' => Auth::id()
        ]);

        $teamVacancy->delete();
        $this->clearVacancyCache($teamVacancyId);

        Log::debug('Succesfully deleted teamVacancy', [
            'teamVacancyData' => $teamVacancyData,
        ]);
    }

    protected function getVacancyCacheKey(int $teamVacancyId): string
    {
        return CACHE_KEY_TEAM_VACANCY_DATA . $teamVacancyId;
    }

    protected function clearVacancyCache(int $teamVacancyId): void
    {
        $this->clearCache($this->getVacancyCacheKey($teamVacancyId));
    }
}
