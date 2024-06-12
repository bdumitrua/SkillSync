<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\User;
use App\Models\TeamApplication;

class TeamApplicationPolicy
{
    protected $teamMemberRepository;
    protected $teamApplicationRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
        TeamApplicationRepositoryInterface $teamApplicationRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->teamApplicationRepository = $teamApplicationRepository;
    }

    /**
     * Determine whether the user can view the application.
     * 
     * @see VIEW_TEAM_APPLICATIONS_GATE
     */
    public function viewTeamApplication(User $user, TeamApplication $teamApplication): Response
    {
        if ($user->id === $teamApplication->user_id) {
            return Response::allow();
        }

        if ($this->teamMemberRepository->userIsModerator($teamApplication->team_id, $user->id)) {
            return Response::allow();
        }

        return Response::deny("Access denied.");
    }

    /**
     * Determine whether the user can apply to the vacancy.
     * 
     * @see APPLY_TO_VACANCY_GATE
     */
    public function applyToVacancy(User $user, int $teamId, int $vacancyId): Response
    {
        if ($this->teamMemberRepository->userIsMember($teamId, $user->id)) {
            return Response::deny("You can't apply for this vacancy, because you're a member of this team.");
        }

        $alreadyApplied = $this->teamApplicationRepository->userAppliedToVacancy(
            $user->id,
            $vacancyId
        );

        if ($alreadyApplied) {
            return Response::denyWithStatus(409, "You already applied to this vacancy.");
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the application.
     * 
     * @see UPDATE_TEAM_APPLICATION_GATE
     */
    public function updateTeamApplication(User $user, TeamApplication $teamApplication): Response
    {
        return $this->teamMemberRepository->userIsModerator($teamApplication->team_id, $user->id)
            ? Response::allow()
            : Response::deny("You have insufficient rigths.");
    }

    /**
     * Determine whether the user can delete the application.
     * 
     * @see DELETE_TEAM_APPLICATION_GATE
     */
    public function deleteTeamApplication(User $user, TeamApplication $teamApplication): Response
    {
        return $user->id === $teamApplication->user_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
