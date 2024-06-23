<?php

namespace App\Observers;

use App\Traits\Cacheable;
use App\Models\ProjectLink;

class ProjectLinkObserver
{
    use Cacheable;

    /**
     * Handle the ProjectLink "created" event.
     */
    public function created(ProjectLink $projectLink): void
    {
        $this->clearMainCache($projectLink->project_id);
    }

    /**
     * Handle the ProjectLink "updated" event.
     */
    public function updated(ProjectLink $projectLink): void
    {
        $this->clearMainCache($projectLink->project_id);
    }

    /**
     * Handle the ProjectLink "deleted" event.
     */
    public function deleted(ProjectLink $projectLink): void
    {
        $this->clearMainCache($projectLink->project_id);
    }

    /**
     * * Recache stuff
     */

    protected function getMainCacheKey(int $projectId): string
    {
        return CACHE_KEY_PROJECT_LINKS_DATA . $projectId;
    }

    protected function clearMainCache(int $projectId): void
    {
        $this->clearCache($this->getMainCacheKey($projectId));
    }
}
