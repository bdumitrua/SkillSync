<?php

namespace App\Services\Team;

use App\Models\TeamScope;
use App\Repositories\Team\Interfaces\TeamScopeRepositoryInterface;
use App\Services\Team\Interfaces\TeamScopeServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamScopeService implements TeamScopeServiceInterface
{
    protected $teamScopeRepository;

    public function __construct(TeamScopeRepositoryInterface $teamScopeRepository)
    {
        $this->teamScopeRepository = $teamScopeRepository;
    }

    public function team(int $teamId): JsonResource
    {
        return JsonResource::collection(
            $this->teamScopeRepository->getByTeamId($teamId)
        );
    }

    public function create(int $teamId): void
    {
        // 
    }

    public function delete(TeamScope $teamScope): void
    {
        // 
    }
}
