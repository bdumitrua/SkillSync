<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\TeamVacancy;
use App\Models\TeamApplication;
use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Http\Requests\Team\CreateTeamApplicationRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamApplicationService implements TeamApplicationServiceInterface
{
    protected $teamApplicationRepository;

    public function __construct(TeamApplicationRepositoryInterface $teamApplicationRepository)
    {
        $this->teamApplicationRepository = $teamApplicationRepository;
    }

    public function show(int $teamApplicationId): JsonResource
    {
        return new JsonResource(
            $this->teamApplicationRepository->getById($teamApplicationId)
        );
    }

    public function team(int $teamId): JsonResource
    {
        return JsonResource::collection(
            $this->teamApplicationRepository->getByTeamId($teamId)
        );
    }

    public function vacancy(int $teamVacancyId): JsonResource
    {
        return JsonResource::collection(
            $this->teamApplicationRepository->getByVacancyId($teamVacancyId)
        );
    }

    public function create(TeamVacancy $teamVacancy, CreateTeamApplicationRequest $request): void
    {
        // 
    }

    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $request): void
    {
        // 
    }

    public function delete(TeamApplication $teamApplication): void
    {
        // 
    }
}
