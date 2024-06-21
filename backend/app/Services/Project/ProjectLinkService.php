<?php

namespace App\Services\Project;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Project\Interfaces\ProjectLinkServiceInterface;
use App\Repositories\Project\Interfaces\ProjectLinkRepositoryInterface;
use App\Models\ProjectLink;
use App\Models\Project;
use App\Http\Resources\Project\ProjectLinkResource;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

class ProjectLinkService implements ProjectLinkServiceInterface
{
    protected $projectLinkRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        ProjectLinkRepositoryInterface $projectLinkRepository,
    ) {
        $this->projectLinkRepository = $projectLinkRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function project(Project $project): JsonResource
    {
        return ProjectLinkResource::collection(
            $this->projectLinkRepository->getByProjectId($project->id)
        );
    }

    public function create(Project $project, CreateProjectLinkDTO $createProjectLinkDTO): void
    {
        Gate::authorize('create', [ProjectLink::class, $project]);

        $this->projectLinkRepository->create($createProjectLinkDTO);
    }

    public function update(Project $project, ProjectLink $projectLink, UpdateProjectLinkDTO $updateProjectLinkDTO): void
    {
        Gate::authorize('update', [ProjectLink::class, $project, $projectLink]);

        $this->projectLinkRepository->update($projectLink, $updateProjectLinkDTO);
    }

    public function delete(Project $project, ProjectLink $projectLink): void
    {
        Gate::authorize('delete', [ProjectLink::class, $project, $projectLink]);

        $this->projectLinkRepository->delete($projectLink);
    }
}
