<?php

namespace App\Repositories\Team\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\TeamLink;
use App\DTO\Team\UpdateTeamLinkDTO;
use App\DTO\Team\CreateTeamLinkDTO;

interface TeamLinkRepositoryInterface
{
    /**
     * @param int $teamId
     * @param bool $isMember
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId, bool $isMember): Collection;

    /**
     * @param CreateTeamLinkDTO $dto
     * 
     * @return TeamLink
     */
    public function create(CreateTeamLinkDTO $dto): TeamLink;

    /**
     * @param TeamLink $teamLink
     * @param UpdateTeamLinkDTO $dto
     * 
     * @return void
     */
    public function update(TeamLink $teamLink, UpdateTeamLinkDTO $dto): void;

    /**
     * @param TeamLink $teamLink
     * 
     * @return void
     */
    public function delete(TeamLink $teamLink): void;
}
