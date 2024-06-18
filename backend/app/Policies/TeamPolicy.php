<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\User;

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
     * Determine whether the user can update the team.
     */
    public function update(User $user, int $teamId): Response
    {
        return $this->admin($user, $teamId);
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, int $teamId): Response
    {
        return $this->admin($user, $teamId);
    }

    /**
     * Determine whether the user can monitor (view/update) team applications.
     * 
     * @see MONITOR_TEAM_APPLICATIONS_GATE
     */
    public function monitorTeamApplications(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team vacancies (create, update, delete).
     * 
     * @see TOUCH_TEAM_VACANCIES_GATE
     */
    public function touchTeamVacancies(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team posts (create, update, delete).
     * 
     * @see TOUCH_TEAM_POSTS_GATE
     */
    public function touchTeamPosts(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team posts (create, update, delete).
     * 
     * @see TOUCH_TEAM_PROJECTS_GATE
     */
    public function touchTeamProjects(User $user, int $teamId): Response
    {
        return $this->admin($user, $teamId);
    }

    /**
     * Determine whether the user can work with team tags (create, delete).
     * 
     * @see TOUCH_TEAM_TAGS_GATE
     */
    public function touchTeamTags(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team links (create, update, delete).
     * 
     * @see TOUCH_TEAM_LINKS_GATE
     */
    public function touchTeamLinks(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can work with team members (add, remove).
     * 
     * @see TOUCH_TEAM_MEMBERS_GATE
     */
    public function touchTeamMembers(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine whether the user can change team chat
     * 
     * @see TOUCH_TEAM_CHAT_GATE
     */
    public function touchTeamChat(User $user, int $teamId): Response
    {
        return $this->moderator($user, $teamId);
    }

    /**
     * Determine if the user is member of this team.
     * 
     * It's public, but prefer to not use outside this policy
     */
    public function member(User $user, int $teamId): Response
    {
        return $this->teamMemberRepository->userIsMember($teamId, $user->id)
            ? Response::allow()
            : Response::deny("You're not member of this team.");
    }

    /**
     * Determine if the user is moderator of this team.
     * 
     * It's public, but prefer to not use outside this policy
     */
    public function moderator(User $user, int $teamId): Response
    {
        return $this->teamMemberRepository->userIsModerator($teamId, $user->id)
            ? Response::allow()
            : Response::deny("You have insufficient rigths.");
    }

    /**
     * Determine if the user is admin of this team.
     * 
     * It's public, but prefer to not use outside this policy
     */
    public function admin(User $user, int $teamId): Response
    {
        $team = $this->teamRepository->getById($teamId);

        $isAdmin = $user->id === $team?->admin_id;

        return $isAdmin
            ? Response::allow()
            : Response::deny("You have insufficient rigths.");
    }
}
