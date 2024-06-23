<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\ProjectMember;

class ProjectMemberObserver
{
    use Cacheable;

    /**
     * Handle the ProjectMember "created" event.
     */
    public function created(ProjectMember $projectMember): void
    {
        $this->clearMainCache($projectMember->project_id);
    }

    /**
     * Handle the ProjectMember "updated" event.
     */
    public function updated(ProjectMember $projectMember): void
    {
        $this->clearMainCache($projectMember->project_id);
    }

    /**
     * Handle the ProjectMember "deleted" event.
     */
    public function deleted(ProjectMember $projectMember): void
    {
        $this->clearMainCache($projectMember->project_id);
    }

    /**
     * * Recache stuff
     */

    protected function getMainCacheKey(int $projectId): string
    {
        return CACHE_KEY_PROJECT_MEMBERS_DATA . $projectId;
    }

    protected function clearMainCache(int $projectId): void
    {
        $this->clearCache($this->getMainCacheKey($projectId));
    }
}
