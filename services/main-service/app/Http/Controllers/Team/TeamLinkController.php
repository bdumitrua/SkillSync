<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
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

    public function create(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamLinkService->create($team->id);
        });
    }

    public function update(TeamLink $teamLink)
    {
        return $this->handleServiceCall(function () use ($teamLink) {
            return $this->teamLinkService->update($teamLink);
        });
    }

    public function delete(TeamLink $teamLink)
    {
        return $this->handleServiceCall(function () use ($teamLink) {
            return $this->teamLinkService->delete($teamLink);
        });
    }
}
