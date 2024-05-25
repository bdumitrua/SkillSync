<?php

namespace App\Services\Team;

use App\Http\Resources\Team\TeamMemberResource;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberService implements TeamMemberServiceInterface
{
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

    public function create(int $teamId): void
    {
        // 
    }

    public function delete(int $teamId, int $userId): void
    {
        // 
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
