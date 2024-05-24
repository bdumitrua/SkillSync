<?php

namespace App\Services\Team;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Team\Interfaces\TeamLinkServiceInterface;
use App\Repositories\Team\Interfaces\TeamLinkRepositoryInterface;
use App\Models\TeamLink;

class TeamLinkService implements TeamLinkServiceInterface
{
    protected $teamLinkRepository;

    public function __construct(TeamLinkRepositoryInterface $teamLinkRepository)
    {
        $this->teamLinkRepository = $teamLinkRepository;
    }

    public function team(int $teamId): JsonResource
    {
        $isMember = true;

        return JsonResource::collection(
            $this->teamLinkRepository->getByTeamId($teamId, $isMember)
        );
    }

    public function create(int $teamId): void
    {
        // 
    }

    public function update(TeamLink $teamLink): void
    {
        // 
    }

    public function delete(TeamLink $teamLink): void
    {
        // 
    }
}
