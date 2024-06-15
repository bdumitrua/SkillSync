<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Services\Project\Interfaces\ProjectServiceInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\CreateProjectRequest;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    private $projectService;

    public function __construct(ProjectServiceInterface $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->projectService->index();
        });
    }

    public function show(Project $project)
    {
        return $this->handleServiceCall(function () use ($project) {
            return $this->projectService->show($project);
        });
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->projectService->team($team);
        });
    }

    public function member(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->projectService->member($user);
        });
    }

    public function author(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->projectService->author($user);
        });
    }

    public function create(CreateProjectRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->projectService->create($request);
        });
    }

    public function update(UpdateProjectRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->projectService->update($request);
        });
    }

    public function delete(Project $project)
    {
        return $this->handleServiceCall(function () use ($project) {
            return $this->projectService->delete($project);
        });
    }
}
