<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;

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
     * @param CreateTeamRequest $request
     * 
     * @return void
     */
    public function create(CreateTeamRequest $request): void;

    /**
     * @param Team $team
     * @param UpdateTeamRequest $request
     * 
     * @return void
     */
    public function update(Team $team, UpdateTeamRequest $request): void;

    /**
     * @param Team $team
     * 
     * @return void
     */
    public function delete(Team $team): void;
}
