<?php

namespace App\Repositories\Team\Interfaces;

use App\DTO\Team\CreateTeamVacancyDTO;
use App\DTO\Team\UpdateTeamVacancyDTO;
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
     * @param CreateTeamVacancyDTO $dto
     * 
     * @return TeamVacancy
     */
    public function create(CreateTeamVacancyDTO $dto): TeamVacancy;

    /**
     * @param TeamVacancy $teamVacancy
     * @param UpdateTeamVacancyDTO $dto
     * 
     * @return void
     */
    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $dto): void;

    /**
     * @param TeamVacancy $teamVacancy
     * 
     * @return void
     */
    public function delete(TeamVacancy $teamVacancy): void;
}
