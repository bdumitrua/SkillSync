<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\TeamMember;
use App\Models\Team;

class TeamMemberPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, ?TeamMember $teamMember): Response
    {
        if (!empty($teamMember)) {
            return Response::deny('User is already member of this team.', 409);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?TeamMember $teamMember): Response
    {
        if (empty($teamMember)) {
            return Response::deny('User is not member of this team.', 400);
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?TeamMember $teamMember, Team $team): Response
    {
        if (empty($teamMember)) {
            return Response::deny('User is not member of this team.', 400);
        }

        if ($team->admin_id === $teamMember->user_id) {
            return Response::deny('Access denied.', 403);
        }

        return Response::allow();
    }
}
