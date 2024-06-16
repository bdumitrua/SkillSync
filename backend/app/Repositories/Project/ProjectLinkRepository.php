<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\UpdateFromDTO;
use App\Repositories\Project\Interfaces\ProjectLinkRepositoryInterface;
use App\Models\ProjectLink;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

class ProjectLinkRepository implements ProjectLinkRepositoryInterface
{
    use UpdateFromDTO;

    public function getByProjectId(int $projectId): Collection
    {
        return ProjectLink::where('project_id', '=', $projectId)->get();
    }

    public function getByProjectsIds(array $projectsIds): Collection
    {
        return ProjectLink::whereIn('project_id', $projectsIds)->get();
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
}
