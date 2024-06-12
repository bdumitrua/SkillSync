<?php

namespace App\Http\Controllers\Team;

use App\DTO\Team\CreateTeamLinkDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamLinkRequest;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
use App\Models\Team;
use App\Models\TeamLink;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use Illuminate\Http\Request;

class TeamLinkController extends Controller
{
    private $teamLinkService;

    public function __construct(TeamLinkServiceInterface $teamLinkService)
    {
        $this->teamLinkService = $teamLinkService;
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamLinkService->team($team->id);
        });
    }

    public function create(Team $team, CreateTeamLinkRequest $request)
    {
        /** @var CreateTeamLinkDTO */
        $createTeamLinkDTO = $request->createDTO();
        $createTeamLinkDTO->setTeamId($team->id);

        return $this->handleServiceCall(function () use ($team, $createTeamLinkDTO) {
            return $this->teamLinkService->create($team->id, $createTeamLinkDTO);
        });
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkRequest $request)
    {
        $updateTeamLinkDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($teamLink, $updateTeamLinkDTO) {
            return $this->teamLinkService->update($teamLink, $updateTeamLinkDTO);
        });
    }

    public function delete(TeamLink $teamLink)
    {
        return $this->handleServiceCall(function () use ($teamLink) {
            return $this->teamLinkService->delete($teamLink);
        });
    }
}
