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
use App\Traits\CreateDTO;

class TeamLinkService implements TeamLinkServiceInterface
{
    use CreateDTO;

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

    public function create(int $teamId, CreateTeamLinkRequest $request): void
    {
        /** @var CreateTeamLinkDTO */
        $createTeamLinkDTO = $this->createDTO($request, CreateTeamLinkDTO::class);
        $createTeamLinkDTO->teamId = $teamId;

        $this->teamLinkRepository->create($createTeamLinkDTO);
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkRequest $request): void
    {
        $updateTeamLinkDTO = $this->createDTO($request, UpdateTeamLinkDTO::class);

        $this->teamLinkRepository->update($teamLink, $updateTeamLinkDTO);
    }

    public function delete(TeamLink $teamLink): void
    {
        $this->teamLinkRepository->delete($teamLink);
    }
}
