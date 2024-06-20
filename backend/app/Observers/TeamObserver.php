<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\Team;

class TeamObserver
{
    use Cacheable;

    /**
     * Handle the Team "created" event.
     */
    public function created(Team $team): void
    {
        $this->clearAllCache($team->id);
    }

    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        $this->clearMainCache($team->id);
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        $this->clearAllCache($team->id);
    }

    /**
     * * Recache stuff
     */

    protected function getMainCacheKey(int $teamId): string
    {
        return CACHE_KEY_TEAM_DATA . $teamId;
    }

    protected function clearMainCache(int $teamId): void
    {
        $this->clearCache($this->getMainCacheKey($teamId));
    }

    protected function getCacheKeys(int $teamId): array
    {
        return [
            CACHE_KEY_TEAM_DATA . $teamId,
            CACHE_KEY_TEAM_LINKS_DATA . $teamId . ':' . false,
            CACHE_KEY_TEAM_LINKS_DATA . $teamId . ':' . true,
            CACHE_KEY_TEAM_TAGS_DATA . $teamId,
            CACHE_KEY_TEAM_VACANCIES_DATA . $teamId,
        ];
    }

    protected function clearAllCache(int $teamId): void
    {
        $cacheKeys = $this->getCacheKeys($teamId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
