<?php

namespace App\Repositories\Interfaces;

use App\Models\TeamLink;
use App\Http\Requests\UpdateTeamLinkRequest;
use Illuminate\Support\Collection;

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
     * @param TeamLink $teamLinkModel
     * 
     * @return TeamLink
     */
    public function create(TeamLink $teamLinkModel): TeamLink;

    /**
     * @param TeamLink $teamLink
     * @param UpdateTeamLinkRequest $updateTeamLinkDto
     * 
     * @return TeamLink|null
     */
    public function update(TeamLink $teamLink, UpdateTeamLinkRequest $updateTeamLinkDto): ?TeamLink;

    /**
     * @param TeamLink $teamLink
     * 
     * @return TeamLink|null
     */
    public function delete(TeamLink $teamLink): ?TeamLink;
}
