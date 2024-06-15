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
     */
    public function view(User $user, TeamApplication $teamApplication): Response
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
     */
    public function create(User $user, int $teamId, int $vacancyId): Response
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
     */
    public function update(User $user, ?int $teamId): Response
    {
        if (empty($teamId)) {
            return Response::deny("You have insufficient rigths.");
        }

        return $this->teamMemberRepository->userIsModerator($teamId, $user->id)
            ? Response::allow()
            : Response::deny("You have insufficient rigths.");
    }

    /**
     * Determine whether the user can delete the application.
     */
    public function delete(User $user, TeamApplication $teamApplication): Response
    {
        return $user->id === $teamApplication->user_id
            ? Response::allow()
            : Response::deny("Access denied.");
    }
}
