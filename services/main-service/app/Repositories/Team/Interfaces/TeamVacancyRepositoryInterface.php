<?php

namespace App\Repositories\Team\Interfaces;

use App\Models\TeamVacancy;
use Illuminate\Database\Eloquent\Collection;

interface TeamVacancyRepositoryInterface
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
     * @return TeamVacancy|null
     */
    public function getById(int $teamVacancyId): ?TeamVacancy;

    /**
     * @param array $teamVacancyIds
     * 
     * @return Collection
     */
    public function getByIds(array $teamVacancyIds): Collection;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return TeamVacancy
     */
    public function create(TeamVacancy $teamVacancy): TeamVacancy;

    /**
     * @param TeamVacancy $teamVacancy
     * @param array $data
     * 
     * @return TeamVacancy
     */
    public function update(TeamVacancy $teamVacancy, array $data): TeamVacancy;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return TeamVacancy|null
     */
    public function delete(TeamVacancy $teamVacancy): ?TeamVacancy;
}
