<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamMemberDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Http\Resources\Team\TeamMemberResource;
use App\Http\Requests\Team\CreateTeamMemberRequest;
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

    public function create(int $teamId, CreateTeamMemberRequest $request): void
    {
        Gate::authorize('touchTeamMembers', $teamId);

        /** @var CreateTeamMemberDTO */
        $createTeamMemberDTO = $this->createDTO($request, CreateTeamMemberDTO::class);
        $createTeamMemberDTO->teamId = $teamId;

        $isMember = $this->teamMemberRepository->userIsMember(
            $createTeamMemberDTO->teamId,
            $createTeamMemberDTO->userId
        );

        if ($isMember) {
            return;
        }

        $this->teamMemberRepository->addMember($createTeamMemberDTO);
    }

    public function delete(int $teamId, int $userId): void
    {
        Gate::authorize('touchTeamMembers', $teamId);

        $membership = $this->teamMemberRepository->getMemberByBothIds(
            $teamId,
            $userId
        );

        if (empty($membership)) {
            // TODO CUSTOM EXCEPTION
            throw new HttpException(400, "This user is not member of this team");
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
