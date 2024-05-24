<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Http\Requests\Team\CreateTeamVacancyRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamVacancyService implements TeamVacancyServiceInterface
{
    protected $teamVacancyRepository;

    public function __construct(TeamVacancyRepositoryInterface $teamVacancyRepository)
    {
        $this->teamVacancyRepository = $teamVacancyRepository;
    }

    public function show(int $teamVacancyId): JsonResource
    {
        return JsonResource::collection(
            $this->teamVacancyRepository->getById($teamVacancyId)
        );
    }

    public function team(int $teamId): JsonResource
    {
        return JsonResource::collection(
            $this->teamVacancyRepository->getByTeamId($teamId)
        );
    }

    public function create(int $teamId, CreateTeamVacancyRequest $request): void
    {
        // 
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyRequest $request): void
    {
        // 
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        // 
    }
}
