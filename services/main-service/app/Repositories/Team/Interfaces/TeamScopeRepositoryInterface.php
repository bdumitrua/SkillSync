<?php

namespace App\Repositories\Team\Interfaces;

use App\Models\TeamScope;
use Illuminate\Database\Eloquent\Collection;

interface TeamScopeRepositoryInterface
{
    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param TeamScope $teamScope
     * 
     * @return TeamScope
     */
    public function create(TeamScope $teamScope): TeamScope;

    /**
     * @param TeamScope $teamScope
     * 
     * @return TeamScope|null
     */
    public function delete(TeamScope $teamScope): ?TeamScope;
}
