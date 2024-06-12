<?php

namespace App\Http\Controllers\Team;

use App\DTO\Team\CreateTeamMemberDTO;
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
        /** @var CreateTeamMemberDTO */
        $createTeamMemberDTO = $request->createDTO();
        $createTeamMemberDTO->setTeamId($team->id);

        return $this->handleServiceCall(function () use ($team, $createTeamMemberDTO) {
            return $this->teamMemberService->create($team->id, $createTeamMemberDTO);
        });
    }

    public function update(Team $team, User $user, UpdateTeamMemberRequest $request)
    {
        $updateTeamMemberDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($team, $user, $updateTeamMemberDTO) {
            return $this->teamMemberService->update($team->id, $user->id, $updateTeamMemberDTO);
        });
    }

    public function delete(Team $team, User $user)
    {
        return $this->handleServiceCall(function () use ($team, $user) {
            return $this->teamMemberService->delete($team, $user->id);
        });
    }
}
