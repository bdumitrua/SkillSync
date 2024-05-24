<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TeamVacancy;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Http\Requests\Team\CreateTeamVacancyRequest;

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
     * @param CreateTeamVacancyRequest $request
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamVacancyRequest $request): void;

    /**
     * @param TeamVacancy $teamVacancy
     * @param UpdateTeamVacancyRequest $request
     * 
     * @return void
     */
    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyRequest $request): void;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return void
     */
    public function delete(TeamVacancy $teamVacancy): void;
}
