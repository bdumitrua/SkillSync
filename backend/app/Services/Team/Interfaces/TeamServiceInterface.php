<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;
use App\DTO\Team\UpdateTeamDTO;
use App\DTO\Team\CreateTeamDTO;

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
