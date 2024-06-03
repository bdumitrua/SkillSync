<?php

namespace App\Services\Team\Interfaces;

use App\DTO\Team\CreateTeamLinkDTO;
use App\DTO\Team\UpdateTeamLinkDTO;
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
     * @param CreateTeamLinkDTO $createTeamLinkDTO
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamLinkDTO $createTeamLinkDTO): void;

    /**
     * @param TeamLink $teamLink
     * @param UpdateTeamLinkDTO $updateTeamLinkDTO
     * 
     * @return void
     */
    public function update(TeamLink $teamLink, UpdateTeamLinkDTO $updateTeamLinkDTO): void;

    /**
     * @param TeamLink $teamLink
     * 
     * @return void
     */
    public function delete(TeamLink $teamLink): void;
}
