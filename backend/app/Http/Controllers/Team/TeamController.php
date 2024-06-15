<?php

namespace App\Http\Controllers\Team;

use App\DTO\Team\CreateTeamDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamRequest;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Traits\Dtoable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function search(Request $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->teamService->search($request);
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
        /** @var CreateTeamDTO */
        $createTeamDTO = $request->createDTO();
        $createTeamDTO->setAdminId(Auth::id());

        return $this->handleServiceCall(function () use ($createTeamDTO) {
            return $this->teamService->create($createTeamDTO);
        });
    }

    public function update(Team $team, UpdateTeamRequest $request)
    {
        $updateTeamDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($team, $updateTeamDTO) {
            return $this->teamService->update($team, $updateTeamDTO);
        });
    }

    public function delete(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamService->delete($team);
        });
    }
}
