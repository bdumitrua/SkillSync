<?php

namespace App\Services\Team\Interfaces;

use App\Models\TeamLink;
use Illuminate\Http\Resources\Json\JsonResource;

interface TeamLinkServiceInterface
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
     * @param TeamLink $teamLink
     * 
     * @return void
     */
    public function update(TeamLink $teamLink): void;

    /**
     * @param TeamLink $teamLink
     * 
     * @return void
     */
    public function delete(TeamLink $teamLink): void;
}
