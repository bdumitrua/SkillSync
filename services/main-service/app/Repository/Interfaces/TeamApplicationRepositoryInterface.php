<?php

namespace App\Repositories\Interfaces;

use App\Models\TeamApplication;
use App\Http\Requests\UpdateTeamApplicationRequest;
use Illuminate\Support\Collection;

interface TeamApplicationRepositoryInterface
{
    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param int $teamVacancyId
     * 
     * @return Collection
     */
    public function getByVacancyId(int $teamVacancyId): Collection;

    /**
     * @param int $teamApplicationId
     * 
     * @return TeamApplication|null
     */
    public function getById(int $teamApplicationId): ?TeamApplication;

    /**
     * @param TeamApplication $teamApplicationModel
     * 
     * @return TeamApplication|null
     */
    public function create(TeamApplication $teamApplicationModel): ?TeamApplication;

    /**
     * @param TeamApplication $teamApplication
     * @param UpdateTeamApplicationRequest $updateTeamApplicationDto
     * 
     * @return TeamApplication
     */
    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $updateTeamApplicationDto): TeamApplication;

    /**
     * @param TeamApplication $teamApplication
     * 
     * @return TeamApplication|null
     */
    public function delete(TeamApplication $teamApplication): ?TeamApplication;
}
