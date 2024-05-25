<?php

namespace App\Services\Team;

use App\Http\Resources\Team\TeamLinkResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TeamLinkService implements TeamLinkServiceInterface
{
    protected $teamLinkRepository;
    protected $teamMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        TeamMemberRepositoryInterface $teamMemberRepository,
        TeamLinkRepositoryInterface $teamLinkRepository,
    ) {
        $this->teamMemberRepository = $teamMemberRepository;
        $this->teamLinkRepository = $teamLinkRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function team(int $teamId): JsonResource
    {
        $isMember = !empty($this->teamMemberRepository->getMemberByBothIds($teamId, $this->authorizedUserId));

        return TeamLinkResource::collection(
            $this->teamLinkRepository->getByTeamId($teamId, $isMember)
        );
    }

    public function create(int $teamId): void
    {
        // 
    }

    public function update(TeamLink $teamLink): void
    {
        // 
    }

    public function delete(TeamLink $teamLink): void
    {
        // 
    }
}
