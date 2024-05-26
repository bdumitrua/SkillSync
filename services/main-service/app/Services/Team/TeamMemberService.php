<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamMemberDTO;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\MembershipException;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Http\Resources\Team\TeamMemberResource;
use App\Http\Requests\Team\CreateTeamMemberRequest;
use App\Models\Team;
use App\Traits\CreateDTO;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TeamMemberService implements TeamMemberServiceInterface
{
    use CreateDTO;

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
    public function create(int $teamId, CreateTeamMemberRequest $request): void
    {
        Gate::authorize(TOUCH_TEAM_MEMBERS_GATE, [Team::class, $teamId]);

        /** @var CreateTeamMemberDTO */
        $createTeamMemberDTO = $this->createDTO($request, CreateTeamMemberDTO::class);
        $createTeamMemberDTO->teamId = $teamId;

        $isMember = $this->teamMemberRepository->userIsMember(
            $createTeamMemberDTO->teamId,
            $createTeamMemberDTO->userId
        );

        if ($isMember) {
            throw new MembershipException("User is already member of this team.");
        }

        $this->teamMemberRepository->addMember($createTeamMemberDTO);
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
        $userIds = $teamMembers->pluck('user_id')->unique()->all();
        $usersData = $this->userRepository->getByIds($userIds);

        foreach ($teamMembers as $member) {
            $member->userData = $usersData->where('id', $member->user_id)->first();
        }

        return $teamMembers;
    }
}
