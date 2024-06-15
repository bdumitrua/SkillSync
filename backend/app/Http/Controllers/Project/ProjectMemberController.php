<?php

namespace App\Http\Controllers\Project;

use Illuminate\Http\Request;
use App\Services\Project\Interfaces\ProjectMemberServiceInterface;
use App\Models\User;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectMemberRequest;
use App\Http\Requests\Project\CreateProjectMemberRequest;
use App\Http\Controllers\Controller;
use App\DTO\Project\UpdateProjectMemberDTO;
use App\DTO\Project\CreateProjectMemberDTO;

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

    public function create(Project $project, User $user, CreateProjectMemberRequest $request)
    {
        /** @var CreateProjectMemberDTO */
        $createProjectMemberDTO = $request->createDTO();
        $createProjectMemberDTO->setProjectId($project->id)->setUserId($user->id);

        return $this->handleServiceCall(function () use ($project, $user, $createProjectMemberDTO) {
            return $this->projectMemberService->create($project, $user, $createProjectMemberDTO);
        });
    }

    public function update(Project $project, User $user, UpdateProjectMemberRequest $request)
    {
        /** @var UpdateProjectMemberDTO */
        $updateProjectMemberDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($project, $user, $updateProjectMemberDTO) {
            return $this->projectMemberService->update($project, $user, $updateProjectMemberDTO);
        });
    }

    public function delete(Project $project, User $user)
    {
        return $this->handleServiceCall(function () use ($project, $user) {
            return $this->projectMemberService->delete($project, $user);
        });
    }
}
