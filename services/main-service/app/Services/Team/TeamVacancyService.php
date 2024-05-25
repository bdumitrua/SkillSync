<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Http\Requests\Team\CreateTeamVacancyRequest;
use App\Http\Resources\Team\TeamVacancyResource;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamVacancyService implements TeamVacancyServiceInterface
{
    protected $teamVacancyRepository;
    protected $teamRepository;

    public function __construct(
        TeamVacancyRepositoryInterface $teamVacancyRepository,
        TeamRepositoryInterface $teamRepository,
    ) {
        $this->teamVacancyRepository = $teamVacancyRepository;
        $this->teamRepository = $teamRepository;
    }

    public function show(int $teamVacancyId): JsonResource
    {
        $teamVacancy = $this->teamVacancyRepository->getById($teamVacancyId);
        $teamVacancy = $this->assembleVacanciesData(new Collection([$teamVacancy]))->first();

        return new TeamVacancyResource($teamVacancy);
    }

    public function team(int $teamId): JsonResource
    {
        $teamVacancies = $this->teamVacancyRepository->getByTeamId($teamId);
        $teamVacancies = $this->assembleVacanciesData($teamVacancies);

        return JsonResource::collection($teamVacancies);
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

    protected function assembleVacanciesData(Collection $teamVacancies): Collection
    {
        $teamIds = $teamVacancies->pluck('team_id')->unique()->all();
        $teamsData = $this->teamRepository->getByIds($teamIds);

        foreach ($teamVacancies as $vacancy) {
            $vacancy->teamData = $teamsData->where('id', $vacancy->team_id)->first();
        }

        return $teamVacancies;
    }
}
