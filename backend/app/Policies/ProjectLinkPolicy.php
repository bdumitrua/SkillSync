<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\ProjectLink;
use App\Models\Project;

class ProjectLinkPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Project $project): Response
    {
        return Gate::inspect('update', [Project::class, $project]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project, ProjectLink $projectLink): Response
    {
        if ($project->id !== $projectLink->project_id) {
            return Response::deny("This links doesn't associate with this project");
        }

        return Gate::inspect('update', [Project::class, $project]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project, ProjectLink $projectLink): Response
    {
        if ($project->id !== $projectLink->project_id) {
            return Response::deny("This links doesn't associate with this project");
        }

        return Gate::inspect('update', [Project::class, $project]);
    }
}
