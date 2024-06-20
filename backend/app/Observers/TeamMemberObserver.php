<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\TeamMember;

class TeamMemberObserver
{
    use Cacheable;

    /**
     * Handle the TeamMember "created" event.
     */
    public function created(TeamMember $teamMember): void
    {
        $this->clearAllCache($teamMember->team_id, $teamMember->user_id);
    }

    /**
     * Handle the TeamMember "updated" event.
     */
    public function updated(TeamMember $teamMember): void
    {
        $this->clearAllCache($teamMember->team_id, $teamMember->user_id);
    }

    /**
     * Handle the TeamMember "deleted" event.
     */
    public function deleted(TeamMember $teamMember): void
    {
        $this->clearAllCache($teamMember->team_id, $teamMember->user_id);
    }

    /**
     * * Recache stuff
     */

    //  At the moment it's only CACHE_KEY_TEAM_USER_MODERATOR, 
    //  but CACHE_KEY_TEAM_USER_MODERATOR isn't MAIN ENTITY, so 'All'
    protected function getCacheKeys(int $teamId, int $userId): array
    {
        return [
            CACHE_KEY_TEAM_USER_MODERATOR . $teamId . ':' . $userId
        ];
    }

    protected function clearAllCache(int $teamId, int $userId): void
    {
        $cacheKeys = $this->getCacheKeys($teamId, $userId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
