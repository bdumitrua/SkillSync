<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\Team;
use App\Http\Requests\Team\UpdateTeamRequest;
use App\Http\Requests\Team\CreateTeamRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamService implements TeamServiceInterface
{
    protected $teamRepository;

    public function __construct(TeamRepositoryInterface $teamRepository)
    {
        $this->teamRepository = $teamRepository;
    }

    public function index(): JsonResource
    {
        return JsonResource::collection(
            $this->teamRepository->getAll()
        );
    }

    public function show(int $teamId): JsonResource
    {
        return new JsonResource(
            $this->teamRepository->getById($teamId)
        );
    }

    public function user(int $userId): JsonResource
    {
        return JsonResource::collection(
            $this->teamRepository->getByUserId($userId)
        );
    }

    public function create(CreateTeamRequest $request): void
    {
        //
    }

    public function update(Team $team, UpdateTeamRequest $request): void
    {
        //
    }

    public function delete(Team $team): void
    {
        //
    }
}
