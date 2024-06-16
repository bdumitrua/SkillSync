<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\DTO\Project\CreateProjectDTO;

class ProjectPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, CreateProjectDTO $createProjectDTO): Response
    {
        return $this->getRights($user, $createProjectDTO->authorType, $createProjectDTO->authorId);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): Response
    {
        return $this->getRights($user, $project->author_type, $project->author_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): Response
    {
        return $this->getRights($user, $project->author_type, $project->author_id);
    }

    protected function getRights(User $user, string $authorType, int $authorId): Response
    {
        if ($authorType === config('entities.user')) {
            return $user->id === $authorId
                ? Response::allow()
                : Response::deny("Access denied.");
        }

        if ($authorType === config('entities.team')) {
            return Gate::allows(TOUCH_TEAM_PROJECTS_GATE, [Team::class, $authorId])
                ? Response::allow()
                : Response::deny("You have insufficient rigths.");
        }

        return Response::deny("Unauthorized action.");
    }
}
