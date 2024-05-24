<?php

namespace App\Repositories\Team;

use App\Repositories\Team\Interfaces\TeamScopeRepositoryInterface;
use App\Models\TeamScope;
use Illuminate\Database\Eloquent\Collection;

class TeamScopeRepository implements TeamScopeRepositoryInterface
{
    public function getByTeamId(int $teamId): Collection
    {
        return new Collection();
    }

    public function create(TeamScope $teamScope): TeamScope
    {
        return new TeamScope();
    }

    public function delete(TeamScope $teamScope): ?TeamScope
    {
        return null;
    }
}
