<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamMemberDTO;
use App\DTO\Team\UpdateTeamMemberDTO;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\MembershipException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Http\Resources\Team\TeamMemberResource;
use App\Http\Requests\Team\CreateTeamMemberRequest;
use App\Http\Requests\Team\UpdateTeamMemberRequest;
use App\Models\Team;
use App\Traits\CreateDTO;
use App\Traits\SetAdditionalData;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    /**
     * @throws MembershipException
     */
    public function create(int $teamId, CreateTeamMemberDTO $createTeamMemberDTO): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamId]);

        $isMember = $this->teamMemberRepository->userIsMember(
            $createTeamMemberDTO->teamId,
            $createTeamMemberDTO->userId
        );

        if ($isMember) {
            throw new MembershipException("User is already member of this team.");
        }

        $this->teamMemberRepository->addMember($createTeamMemberDTO);
    }

    public function update(int $teamId, int $userId, UpdateTeamMemberDTO $updateTeamMemberDTO): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamId]);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $teamId,
            $userId
        );

        if (empty($membership)) {
            throw new MembershipException("User is not member of this team");
        }

        $this->teamMemberRepository->updateMember($membership, $updateTeamMemberDTO);
    }

    /**
     * @throws MembershipException
     * @throws AccessDeniedException
     */
    public function delete(Team $team, int $userId): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $team->id]);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $team->id,
            $userId
        );

        if (empty($membership)) {
            throw new MembershipException("User is not member of this team");
        }

        if ($team->admin_id === $userId) {
            throw new AccessDeniedException();
        }

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
        $canChange = Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamMembers->first()?->team_id]);

        foreach ($teamMembers as $teamMember) {
            $teamMember->canChange = $canChange || Auth::id() === $teamMember->user_id;
        }
    }
}
