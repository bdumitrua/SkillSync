<?php

namespace App\Repositories\Team\Interfaces;

use App\DTO\Team\CreateTeamDTO;
use App\DTO\Team\UpdateTeamDTO;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $teamId
     * 
     * @return Team|null
     */
    public function getById(int $teamId): ?Team;

    /**
     * @param array $teamIds
     * 
     * @return Collection
     */
    public function getByIds(array $teamIds): Collection;

    /**
     * @param string $teamName
     * 
     * @return Team|null
     */
    public function getByName(string $teamName): ?Team;

    /**
     * @param int $chatId
     * 
     * @return Team|null
     */
    public function getByChatId(int $chatId): ?Team;

    /**
     * @param CreateTeamDTO $dto
     * 
     * @return Team
     */
    public function create(CreateTeamDTO $dto): Team;

    /**
     * @param Team $team
     * @param UpdateTeamDTO $dto
     * 
     * @return void
     */
    public function update(Team $team, UpdateTeamDTO $dto): void;

    /**
     * @param Team $team
     * 
     * @return void
     */
    public function delete(Team $team): void;
}
