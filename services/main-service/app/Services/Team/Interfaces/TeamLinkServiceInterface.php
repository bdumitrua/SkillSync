<?php

namespace App\Services\Team\Interfaces;

use App\Http\Requests\Team\CreateTeamLinkRequest;
use App\Http\Requests\Team\UpdateTeamLinkRequest;
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
     * @param CreateTeamLinkRequest $request
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamLinkRequest $request): void;

    /**
     * @param TeamLink $teamLink
     * @param UpdateTeamLinkRequest $request
     * 
     * @return void
     */
    public function update(TeamLink $teamLink, UpdateTeamLinkRequest $request): void;

    /**
     * @param TeamLink $teamLink
     * 
     * @return void
     */
    public function delete(TeamLink $teamLink): void;
}
