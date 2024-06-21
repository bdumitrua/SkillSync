<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Traits\Cacheable;
use App\Models\Project;

class ProjectObserver
{
    use Cacheable;

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->clearAllCache($project->id);
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        // 
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $this->clearAllCache($project->id);
    }

    /**
     * * Recache stuff
     */

    protected function getCacheKeys(int $projectId): array
    {
        return [
            CACHE_KEY_PROJECT_MEMBERS_DATA . $projectId,
            CACHE_KEY_PROJECT_LINKS_DATA . $projectId,
        ];
    }

    protected function clearAllCache(int $projectId): void
    {
        $cacheKeys = $this->getCacheKeys($projectId);

        foreach ($cacheKeys as $cacheKey) {
            $this->clearCache($cacheKey);
        }
    }
}
