<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\Team\CreateTeamMemberRequest;

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
     * 
     * @return void
     */
    public function delete(int $teamId, int $userId): void;
}
