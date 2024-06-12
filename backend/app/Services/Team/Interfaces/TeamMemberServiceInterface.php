<?php

namespace App\Services\Team\Interfaces;

use App\DTO\Team\CreateTeamMemberDTO;
use App\DTO\Team\UpdateTeamMemberDTO;
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
     * @param CreateTeamMemberDTO $createTeamMemberDTO
     * 
     * @return void
     */
    public function create(int $teamId, CreateTeamMemberDTO $createTeamMemberDTO): void;

    /**
     * @param int $teamId
     * @param int $userId
     * @param UpdateTeamMemberDTO $updateTeamMemberDTO
     * 
     * @return void
     */
    public function update(int $teamId, int $userId, UpdateTeamMemberDTO $updateTeamMemberDTO): void;

    /**
     * @param Team $team
     * @param int $userId
     * 
     * @return void
     */
    public function delete(Team $team, int $userId): void;
}
