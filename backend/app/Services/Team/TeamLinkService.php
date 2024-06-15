<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamLinkDTO;
use App\DTO\Team\UpdateTeamLinkDTO;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Repositories\Team\Interfaces\TeamMemberRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;
use App\Http\Resources\Team\TeamLinkResource;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
use App\Http\Requests\Team\CreateTeamLinkRequest;
use App\Models\Team;
use App\Traits\Dtoable;
use Illuminate\Support\Facades\Gate;

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
        $isMember = $this->teamMemberRepository->userIsMember($teamId, $this->authorizedUserId);

        return TeamLinkResource::collection(
            $this->teamLinkRepository->getByTeamId($teamId, $isMember)
        );
    }

    public function create(int $teamId, CreateTeamLinkDTO $createTeamLinkDTO): void
    {
        Gate::authorize(TOUCH_TEAM_LINKS_GATE, [Team::class, $teamId]);

        $this->teamLinkRepository->create($createTeamLinkDTO);
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkDTO $updateTeamLinkDTO): void
    {
        Gate::authorize(TOUCH_TEAM_LINKS_GATE, [Team::class, $teamLink->team_id]);

        $this->teamLinkRepository->update($teamLink, $updateTeamLinkDTO);
    }

    public function delete(TeamLink $teamLink): void
    {
        Gate::authorize(TOUCH_TEAM_LINKS_GATE, [Team::class, $teamLink->team_id]);

        $this->teamLinkRepository->delete($teamLink);
    }
}
