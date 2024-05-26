<?php

namespace App\Policies;

use App\Models\TeamApplication;
use App\Models\User;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Auth\Access\Response;

class TeamApplicationPolicy
{
    protected $teamMemberRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function viewTeamApplication(User $user, TeamApplication $teamApplication): bool
    {
        return $this->getRights($user, $teamApplication);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function deleteTeamApplication(User $user, TeamApplication $teamApplication): bool
    {
        return $this->getRights($user, $teamApplication);
    }

    private function getRights(User $user, TeamApplication $teamApplication): bool
    {
        if ($user->id === $teamApplication->user_id) {
            return true;
        }

        if ($this->teamMemberRepository->userIsModerator($teamApplication->team_id, $user->id)) {
            return true;
        }

        return false;
    }
}
