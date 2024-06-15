<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Services\Project\Interfaces\ProjectLinkServiceInterface;
use App\Models\ProjectLink;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectLinkRequest;
use App\Http\Requests\Project\CreateProjectLinkRequest;
use App\Http\Controllers\Controller;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

class ProjectLinkController extends Controller
{
    private $projectLinkService;

    public function __construct(ProjectLinkServiceInterface $projectLinkService)
    {
        $this->projectLinkService = $projectLinkService;
    }

    public function project(Project $project)
    {
        return $this->handleServiceCall(function () use ($project) {
            return $this->projectLinkService->project($project);
        });
    }

    public function create(Project $project, CreateProjectLinkRequest $request)
    {
        /** @var CreateProjectLinkDTO */
        $createProjectLinkDTO = $request->createDTO();
        $createProjectLinkDTO->setProjectId($project->id);

        return $this->handleServiceCall(function () use ($project, $createProjectLinkDTO) {
            return $this->projectLinkService->create($project, $createProjectLinkDTO);
        });
    }

    public function update(Project $project, ProjectLink $projectLink, UpdateProjectLinkRequest $request)
    {
        /** @var UpdateProjectLinkDTO */
        $updateProjectLinkDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($project, $projectLink, $updateProjectLinkDTO) {
            return $this->projectLinkService->update($project, $projectLink, $updateProjectLinkDTO);
        });
    }

    public function delete(Project $project, ProjectLink $projectLink)
    {
        return $this->handleServiceCall(function () use ($project, $projectLink) {
            return $this->projectLinkService->delete($project, $projectLink);
        });
    }
}
