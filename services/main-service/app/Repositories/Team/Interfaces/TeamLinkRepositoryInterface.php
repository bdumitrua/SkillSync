<?php

namespace App\Repositories\Team\Interfaces;

use App\DTO\Team\CreateTeamLinkDTO;
use App\DTO\Team\UpdateTeamLinkDTO;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
use App\Models\TeamLink;
use Illuminate\Database\Eloquent\Collection;

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
     * @param int $teamLinkId
     * 
     * @return TeamLink|null
     */
    public function getById(int $teamLinkId): ?TeamLink;

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
