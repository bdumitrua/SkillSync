<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamScope;
use App\Services\Team\Interfaces\TeamScopeServiceInterface;
use Illuminate\Http\Request;

class TeamScopeController extends Controller
{
    private $teamScopeService;

    public function __construct(TeamScopeServiceInterface $teamScopeService)
    {
        $this->teamScopeService = $teamScopeService;
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamScopeService->team($team->id);
        });
    }

    public function create(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamScopeService->create($team->id);
        });
    }

    public function delete(TeamScope $teamScope)
    {
        return $this->handleServiceCall(function () use ($teamScope) {
            return $this->teamScopeService->delete($teamScope);
        });
    }
}
