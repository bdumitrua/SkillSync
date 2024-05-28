<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamMemberRequest;
use App\Http\Requests\Team\UpdateTeamMemberRequest;
use App\Models\Team;
use App\Models\User;
use App\Services\Team\Interfaces\TeamMemberServiceInterface;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    private $teamMemberService;

    public function __construct(TeamMemberServiceInterface $teamMemberService)
    {
        $this->teamMemberService = $teamMemberService;
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamMemberService->team($team->id);
        });
    }

    public function create(Team $team, CreateTeamMemberRequest $request)
    {
        return $this->handleServiceCall(function () use ($team, $request) {
            return $this->teamMemberService->create($team->id, $request);
        });
    }

    public function update(Team $team, User $user, UpdateTeamMemberRequest $request)
    {
        return $this->handleServiceCall(function () use ($team, $user, $request) {
            return $this->teamMemberService->update($team->id, $user->id, $request);
        });
    }

    public function delete(Team $team, User $user)
    {
        return $this->handleServiceCall(function () use ($team, $user) {
            return $this->teamMemberService->delete($team, $user->id);
        });
    }
}
