<?php

namespace App\Services\Team\Interfaces;

use App\DTO\Team\CreateTeamDTO;
use App\DTO\Team\UpdateTeamDTO;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;
use Illuminate\Http\Request;

interface TeamServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function show(int $teamId): JsonResource;

    /**
     * @param Request $request
     * 
     * @return JsonResource
     */
    public function search(Request $request): JsonResource;

    /**
     * @param int $userId
     * 
     * @return JsonResource
     */
    public function user(int $userId): JsonResource;

    /**
     * @param int $teamId
     * 
     * @return JsonResource
     */
    public function subscribers(int $teamId): JsonResource;

    /**
     * @param CreateTeamDTO $createTeamDTO
     * 
     * @return void
     */
    public function create(CreateTeamDTO $createTeamDTO): void;

    /**
     * @param Team $team
     * @param UpdateTeamDTO $updateTeamDTO
     * 
     * @return void
     */
    public function update(Team $team, UpdateTeamDTO $updateTeamDTO): void;

    /**
     * @param Team $team
     * 
     * @return void
     */
    public function delete(Team $team): void;
}
