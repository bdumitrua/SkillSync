<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Project\Interfaces\ProjectLinkRepositoryInterface;
use App\Models\ProjectLink;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

class ProjectLinkRepository implements ProjectLinkRepositoryInterface
{
    use Updateable, Cacheable;

    public function getByProjectId(int $projectId): Collection
    {
        $cacheKey = $this->getProjectLinksCacheKey($projectId);
        return $this->getCachedData($cacheKey, CACHE_TIME_PROJECT_LINKS_DATA, function () use ($projectId) {
            return ProjectLink::where('project_id', '=', $projectId)->get();
        });
    }

    public function getByProjectsIds(array $projectsIds): Collection
    {
        return $this->getCachedCollection($projectsIds, function ($projectId) {
            return $this->getByProjectId($projectId);
        });
    }

    public function create(CreateProjectLinkDTO $createProjectLinkDTO): void
    {
        Log::debug('Creating project link from dto', [
            'createProjectLinkDTO' => $createProjectLinkDTO->toArray()
        ]);

        ProjectLink::create(
            $createProjectLinkDTO->toArray()
        );
    }

    public function update(ProjectLink $projectLink, UpdateProjectLinkDTO $updateProjectLinkDTO): void
    {
        Log::debug('Updating project link from dto', [
            'updateProjectLinkDTO' => $updateProjectLinkDTO->toArray()
        ]);

        $this->updateFromDto($projectLink, $updateProjectLinkDTO);
    }

    public function delete(ProjectLink $projectLink): void
    {
        Log::debug('Deleting projectLink', [
            'projectLink' => $projectLink->toArray(),
        ]);

        $projectLink->delete();
    }

    protected function getProjectLinksCacheKey(int $projectId): string
    {
        return CACHE_KEY_PROJECT_LINKS_DATA . $projectId;
    }
}
