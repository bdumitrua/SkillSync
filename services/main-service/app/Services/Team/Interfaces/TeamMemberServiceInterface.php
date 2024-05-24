<?php

namespace App\Services\Team\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;

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
     * 
     * @return void
     */
    public function create(int $teamId): void;

    /**
     * @param int $teamId
     * 
     * @return void
     */
    public function delete(int $teamId): void;
}
