<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectMemberRequest;
use App\Http\Controllers\Controller;

class ProjectMemberController extends Controller
{
    private $projectMemberService;

    public function __construct(ProjectMemberServiceInterface $projectMemberService)
    {
        $this->projectMemberService = $projectMemberService;
    }

    public function project(Project $project)
    {
        return $this->handleServiceCall(function () use ($project) {
            return $this->projectMemberService->project($project);
        });
    }

    public function update(Project $project, User $user, UpdateProjectMemberRequest $request)
    {
        return $this->handleServiceCall(function () use ($project, $user, $request) {
            return $this->projectMemberService->update($project, $user, $request);
        });
    }

    public function delete(Project $project, User $user)
    {
        return $this->handleServiceCall(function () use ($project, $user) {
            return $this->projectMemberService->delete($project, $user);
        });
    }
}
