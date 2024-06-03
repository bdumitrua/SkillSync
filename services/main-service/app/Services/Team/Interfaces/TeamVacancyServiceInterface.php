<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TeamVacancy;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Http\Requests\Team\CreateTeamVacancyRequest;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;

interface TeamVacancyServiceInterface
{
    /**
     * @param int $teamVacancyId
     * 
     * @return JsonResource
     */
    public function show(int $teamVacancyId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param int $teamId
     * @param CreateTeamVacancyDTO $createTeamVacancyDTO
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamVacancyDTO $createTeamVacancyDTO): void;

    /**
     * @param TeamVacancy $teamVacancy
     * @param UpdateTeamVacancyDTO $updateTeamVacancyDTO
     * 
     * @return void
     */
    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $updateTeamVacancyDTO): void;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return void
     */
    public function delete(TeamVacancy $teamVacancy): void;
}
