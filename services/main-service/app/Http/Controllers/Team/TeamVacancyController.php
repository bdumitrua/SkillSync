<?php

namespace App\Http\Controllers\Team;

use App\DTO\Team\CreateTeamVacancyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\CreateTeamVacancyRequest;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Models\Team;
use App\Models\TeamVacancy;
use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use Illuminate\Http\Request;

class TeamVacancyController extends Controller
{
    private $teamVacancyService;

    public function __construct(TeamVacancyServiceInterface $teamVacancyService)
    {
        $this->teamVacancyService = $teamVacancyService;
    }

    public function show(TeamVacancy $teamVacancy)
    {
        return $this->handleServiceCall(function () use ($teamVacancy) {
            return $this->teamVacancyService->show($teamVacancy->id);
        });
    }

    public function team(Team $team)
    {
        return $this->handleServiceCall(function () use ($team) {
            return $this->teamVacancyService->team($team->id);
        });
    }

    public function create(Team $team, CreateTeamVacancyRequest $request)
    {
        /** @var CreateTeamVacancyDTO */
        $createTeamVacancyDTO = $request->createDTO();
        $createTeamVacancyDTO->setTeamId($team->id);

        return $this->handleServiceCall(function () use ($team, $createTeamVacancyDTO) {
            return $this->teamVacancyService->create($team->id, $createTeamVacancyDTO);
        });
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyRequest $request)
    {
        $updateTeamVacancyDTO = $request->createDTO();

        return $this->handleServiceCall(function () use ($teamVacancy, $updateTeamVacancyDTO) {
            return $this->teamVacancyService->update($teamVacancy, $updateTeamVacancyDTO);
        });
    }

    public function delete(TeamVacancy $teamVacancy)
    {
        return $this->handleServiceCall(function () use ($teamVacancy) {
            return $this->teamVacancyService->delete($teamVacancy);
        });
    }
}
