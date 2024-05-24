<?php

namespace App\Repositories\Interfaces;

use App\Models\TeamScope;
use Illuminate\Support\Collection;

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
