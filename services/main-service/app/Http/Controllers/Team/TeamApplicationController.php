<?php

namespace App\Http\Controllers\Team;

use App\DTO\Team\CreateTeamApplicationDTO;
use App\Enums\TeamApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamApplicationRequest;
use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Models\Team;
use App\Models\TeamApplication;
use App\Models\TeamVacancy;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamApplicationController extends Controller
{
    private $teamApplicationService;

    public function __construct(TeamApplicationServiceInterface $teamApplicationService)
    {
        $this->teamApplicationService = $teamApplicationService;
    }

    public function show(TeamApplication $teamApplication)
    {
        return $this->handleServiceCall(function () use ($teamApplication) {
            return $this->teamApplicationService->show($teamApplication->id);
        });
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamApplicationService->team($team->id);
        });
    }

    public function vacancy(TeamVacancy $teamVacancy)
    {
        return $this->handleServiceCall(function () use ($teamVacancy) {
            return $this->teamApplicationService->vacancy($teamVacancy);
        });
    }

    public function create(CreateTeamApplicationRequest $request)
    {
        /** @var CreateTeamApplicationDTO */
        $createTeamApplicationDTO = $request->createDTO();
        $createTeamApplicationDTO->setUserId(Auth::id());

        return $this->handleServiceCall(function () use ($createTeamApplicationDTO) {
            return $this->teamApplicationService->create($createTeamApplicationDTO);
        });
    }

    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $request)
    {
        $newStatus = $request->status;

        return $this->handleServiceCall(function () use ($teamApplication, $newStatus) {
            return $this->teamApplicationService->update($teamApplication, $newStatus);
        });
    }

    public function delete(TeamApplication $teamApplication)
    {
        return $this->handleServiceCall(function () use ($teamApplication) {
            return $this->teamApplicationService->delete($teamApplication);
        });
    }
}
