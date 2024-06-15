<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Services\Project\Interfaces\ProjectLinkServiceInterface;
use App\Models\ProjectLink;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectLinkRequest;
use App\Http\Controllers\Controller;

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

    public function update(Project $project, ProjectLink $projectLink, UpdateProjectLinkRequest $request)
    {
        return $this->handleServiceCall(function () use ($project, $projectLink, $request) {
            return $this->projectLinkService->update($project, $projectLink, $request);
        });
    }

    public function delete(Project $project, ProjectLink $projectLink)
    {
        return $this->handleServiceCall(function () use ($project, $projectLink) {
            return $this->projectLinkService->delete($project, $projectLink);
        });
    }
}
