<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Team;
use App\Http\Requests\Team\CreateTeamMemberRequest;
use App\Http\Requests\Team\UpdateTeamMemberRequest;

interface TeamMemberServiceInterface
{
    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function team(int $teamId): JsonResource;

    /**
     * @param int $teamId
     * @param CreateTeamMemberRequest $request
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamMemberRequest $request): void;

    /**
     * @param int $teamId
     * @param int $userId
     * @param UpdateTeamMemberRequest $request
     * 
     * @return void
     */
    public function update(int $teamId, int $userId, UpdateTeamMemberRequest $request): void;

    /**
     * @param Team $team
     * @param int $userId
     * 
     * @return void
     */
    public function delete(Team $team, int $userId): void;
}
