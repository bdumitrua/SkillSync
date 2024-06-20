<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\TeamVacancy;

class TeamVacancyObserver
{
    use Cacheable;

    /**
     * Handle the TeamVacancy "created" event.
     */
    public function created(TeamVacancy $teamVacancy): void
    {
        $this->clearAllCache($teamVacancy->id, $teamVacancy->team_id);
    }

    /**
     * Handle the TeamVacancy "updated" event.
     */
    public function updated(TeamVacancy $teamVacancy): void
    {
        $this->clearAllCache($teamVacancy->id, $teamVacancy->team_id);
    }

    /**
     * Handle the TeamVacancy "deleted" event.
     */
    public function deleted(TeamVacancy $teamVacancy): void
    {
        $this->clearAllCache($teamVacancy->id, $teamVacancy->team_id);
    }

    /**
     * * Recache stuff
     */

    protected function getCacheKeys(int $teamVacancyId, int $teamId): array
    {
        return [
            CACHE_KEY_VACANCY_DATA . $teamVacancyId,
            CACHE_KEY_TEAM_VACANCIES_DATA . $teamId,
        ];
    }

    protected function clearAllCache(int $teamVacancyId, int $teamId): void
    {
        $cacheKeys = $this->getCacheKeys($teamVacancyId, $teamId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
