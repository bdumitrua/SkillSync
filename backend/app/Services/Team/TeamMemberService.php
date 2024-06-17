<?php

namespace App\Services\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\SetAdditionalData;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Models\TeamMember;
use App\Models\Team;
use App\Http\Resources\Team\TeamMemberResource;
use App\DTO\Team\UpdateTeamMemberDTO;
use App\DTO\Team\CreateTeamMemberDTO;

class TeamMemberService implements TeamMemberServiceInterface
{
    use SetAdditionalData;

    protected $teamMemberRepository;
    protected $userRepository;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
        UserRepositoryInterface $userRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->userRepository = $userRepository;
    }

    public function team(int $teamId): JsonResource
    {
        $teamMembers = $this->teamMemberRepository->getByTeamId($teamId);
        $teamMembers = $this->assebmleMembersData($teamMembers);

        return TeamMemberResource::collection(
            $teamMembers
        );
    }

    public function create(int $teamId, CreateTeamMemberDTO $createTeamMemberDTO): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamId]);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $createTeamMemberDTO->teamId,
            $createTeamMemberDTO->userId
        );

        Gate::authorize('create', [TeamMember::class, $membership]);

        $this->teamMemberRepository->addMember($createTeamMemberDTO);
    }

    public function update(int $teamId, int $userId, UpdateTeamMemberDTO $updateTeamMemberDTO): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamId]);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $teamId,
            $userId
        );

        Gate::authorize('update', [TeamMember::class, $membership]);

        $this->teamMemberRepository->updateMember($membership, $updateTeamMemberDTO);
    }

    public function delete(Team $team, int $userId): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $team->id]);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $team->id,
            $userId
        );

        Gate::authorize('delete', [TeamMember::class, $membership, $team]);

        $this->teamMemberRepository->removeMember($membership);
    }

    protected function assebmleMembersData(Collection $teamMembers): Collection
    {
        $this->setCollectionEntityData($teamMembers, 'user_id', 'userData', $this->userRepository);
        $this->setMembershipRights($teamMembers);

        return $teamMembers;
    }

    protected function setMembershipRights(Collection &$teamMembers): void
    {
        $canChange = Gate::allows(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamMembers->first()?->team_id]);

        foreach ($teamMembers as $teamMember) {
            $teamMember->canChange = $canChange || Auth::id() === $teamMember->user_id;
        }
    }
}
