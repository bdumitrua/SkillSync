<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\DTO\Team\CreateTeamDTO;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;

class TeamPolicy
{
    protected $teamRepository;
    protected $teamMemberRepository;

    public function __construct(
        TeamRepositoryInterface $teamRepository,
        TeamMemberRepositoryInterface $teamMemberRepository,
    ) {
        $this->teamRepository = $teamRepository;
        $this->teamMemberRepository = $teamMemberRepository;
    }

    /**
     * Determine whether the user can create a team.
     */
    public function createTeam(User $user, CreateTeamDTO $dto): bool
    {
        // Return true, if team with that name doesn't exist
        return $user->id === $dto->adminId && empty($this->teamRepository->getByName($dto->name));
    }

    /**
     * Determine whether the user can update the team.
     */
    public function updateTeam(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function deleteTeam(User $user, int $teamId): bool
    {
        return $this->admin($user, $teamId);
    }

    /**
     * Determine whether the user can monitor (view/update) team applications.
     */
    public function monitorTeamApplications(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team vacancies (create, update, delete).
     */
    public function touchTeamVacancies(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team posts (create, update, delete).
     */
    public function touchTeamPosts(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team tags (create, delete).
     */
    public function touchTeamTags(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team links (create, update, delete).
     */
    public function touchTeamLinks(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team members (add, remove).
     */
    public function touchTeamMembers(User $user, int $teamId): bool
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine if the user is member of this team.
     */
    public function member(User $user, int $teamId): bool
    {
        return $this->teamMemberRepository->userIsMember($teamId, $user->id);
    }

    /**
     * Determine if the user is moderator of this team.
     */
    // TODO Move to global var
    public function moderator(User $user, int $teamId): bool
    {
        return $this->teamMemberRepository->userIsModerator($teamId, $user->id);
    }

    /**
     * Determine if the user is admin of this team.
     */
    public function admin(User $user, int $teamId): bool
    {
        $team = $this->teamRepository->getById($teamId);

        return $user->id === $team?->admin_id;
    }
}
