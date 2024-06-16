<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Repositories\Project\Interfaces\ProjectMemberRepositoryInterface;
use App\Models\User;
use App\Models\ProjectMember;
use App\Models\Project;

class ProjectMemberPolicy
{
    protected $projectMemberRepository;

    public function __construct(ProjectMemberRepositoryInterface $projectMemberRepository)
    {
        $this->projectMemberRepository = $projectMemberRepository;
    }
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project, ?ProjectMember $projectMember): Response
    {
        if (!empty($projectMember)) {
            return Response::deny("User is already member of this project");
        }

        return Gate::inspect('update', [Project::class, $project]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project, ?ProjectMember $projectMember): Response
    {
        if (empty($projectMember)) {
            return Response::deny("User is not member of this project");
        }

        return Gate::inspect('update', [Project::class, $project]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project, ?ProjectMember $projectMember): Response
    {
        if (empty($projectMember)) {
            return Response::deny("User is not member of this project");
        }

        // You can remove project author from members, anyway his author rights remain

        return Gate::inspect('update', [Project::class, $project]);
    }
}
