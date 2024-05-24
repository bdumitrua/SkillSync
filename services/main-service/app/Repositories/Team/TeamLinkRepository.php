<?php

namespace App\Repositories\Team;

use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
use App\Models\TeamLink;
use Illuminate\Database\Eloquent\Collection;

class TeamLinkRepository implements TeamLinkRepositoryInterface
{
    public function getByTeamId(int $teamId, bool $isMember): Collection
    {
        return new Collection();
    }

    public function getById(int $teamLinkId): ?TeamLink
    {
        return null;
    }

    public function create(TeamLink $teamLinkModel): TeamLink
    {
        return new TeamLink();
    }

    public function update(TeamLink $teamLink, UpdateTeamLinkRequest $updateTeamLinkDto): ?TeamLink
    {
        return null;
    }

    public function delete(TeamLink $teamLink): ?TeamLink
    {
        return null;
    }
}
