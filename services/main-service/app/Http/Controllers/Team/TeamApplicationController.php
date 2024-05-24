<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamApplicationRequest;
use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Models\Team;
use App\Models\TeamApplication;
use App\Models\TeamVacancy;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use Illuminate\Http\Request;

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
            return $this->teamApplicationService->vacancy($teamVacancy->id);
        });
    }

    public function create(TeamVacancy $teamVacancy, CreateTeamApplicationRequest $request)
    {
        return $this->handleServiceCall(function () use ($teamVacancy, $request) {
            return $this->teamApplicationService->create($teamVacancy, $request);
        });
    }

    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $request)
    {
        return $this->handleServiceCall(function () use ($teamApplication, $request) {
            return $this->teamApplicationService->update($teamApplication, $request);
        });
    }

    public function delete(TeamApplication $teamApplication)
    {
        return $this->handleServiceCall(function () use ($teamApplication) {
            return $this->teamApplicationService->delete($teamApplication);
        });
    }
}
