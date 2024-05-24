<?php

namespace App\Services\Team;

use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberService implements TeamMemberServiceInterface
{
    protected $teamMemberRepository;

    public function __construct(TeamMemberRepositoryInterface $teamMemberRepository)
    {
        $this->teamMemberRepository = $teamMemberRepository;
    }

    public function team(int $teamId): JsonResource
    {
        return JsonResource::collection(
            $this->teamMemberRepository->getByTeamId($teamId)
        );
    }

    public function create(int $teamId): void
    {
        // 
    }

    public function delete(int $teamId): void
    {
        // 
    }
}
