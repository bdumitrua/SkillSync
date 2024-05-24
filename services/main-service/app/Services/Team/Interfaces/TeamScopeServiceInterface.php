<?php

namespace App\Services\Team\Interfaces;

use App\Models\TeamScope;
use Illuminate\Http\Resources\Json\JsonResource;

interface TeamScopeServiceInterface
{
    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return void
     */
    public function create(int $teamId): void;

    /**
     * @param TeamScope $teamScope
     * 
     * @return void
     */
    public function delete(TeamScope $teamScope): void;
}
