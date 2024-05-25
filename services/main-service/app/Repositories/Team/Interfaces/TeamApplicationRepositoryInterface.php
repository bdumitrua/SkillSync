<?php

namespace App\Repositories\Team\Interfaces;

use App\DTO\Team\CreateTeamApplicationDTO;
use App\Models\TeamApplication;
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
     * @param int $userId
     * @param int $teamVacancyId
     * 
     * @return bool
     */
    public function userAppliedToVacancy(int $userId, int $teamVacancyId): bool;

    /**
     * @param CreateTeamApplicationDTO $dto
     * 
     * @return TeamApplication
     */
    public function create(CreateTeamApplicationDTO $dto): TeamApplication;

    /**
     * @param TeamApplication $teamApplication
     * @param string $status
     * 
     * @return void
     */
    public function update(TeamApplication $teamApplication, string $status): void;

    /**
     * @param TeamApplication $teamApplication
     * 
     * @return void
     */
    public function delete(TeamApplication $teamApplication): void;
}
