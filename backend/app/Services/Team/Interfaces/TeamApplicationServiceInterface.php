<?php

namespace App\Services\Team\Interfaces;

use App\DTO\Team\CreateTeamApplicationDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\TeamVacancy;
use App\Models\TeamApplication;
use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Http\Requests\Team\CreateTeamApplicationRequest;

interface TeamApplicationServiceInterface
{
    /**
     * @param int $teamApplicationId
     * 
     * @return JsonResource
     */
    public function show(int $teamApplicationId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return JsonResource
     */
    public function vacancy(TeamVacancy $teamVacancy): JsonResource;

    /**
     * @param CreateTeamApplicationDTO $createTeamApplicationDTO
     * 
     * @return void
     */
    public function create(CreateTeamApplicationDTO $createTeamApplicationDTO): void;

    /**
     * @param TeamApplication $teamApplication
     * @param string $newStatus
     * 
     * @return void
     */
    public function update(TeamApplication $teamApplication, string $newStatus): void;

    /**
     * @param TeamApplication $teamApplication
     * 
     * @return void
     */
    public function delete(TeamApplication $teamApplication): void;
}
