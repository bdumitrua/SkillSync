<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\Team\Interfaces\TeamServiceInterface;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    private $teamService;

    public function __construct(TeamServiceInterface $teamService)
    {
        $this->teamService = $teamService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->teamService->index();
        });
    }

    public function show(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamService->show($team->id);
        });
    }

    public function user(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->teamService->user($user->id);
        });
    }

    public function subscribers(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamService->subscribers($team->id);
        });
    }

    public function create(CreateTeamRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->teamService->create($request);
        });
    }

    public function update(Team $team, UpdateTeamRequest $request)
    {
        return $this->handleServiceCall(function () use ($team, $request) {
            return $this->teamService->update($team, $request);
        });
    }

    public function delete(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamService->delete($team);
        });
    }
}
