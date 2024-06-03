<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamVacancyDTO;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Models\TeamVacancy;
use App\Http\Requests\Team\UpdateTeamVacancyRequest;
use App\Http\Requests\Team\CreateTeamVacancyRequest;
use App\Http\Resources\Team\TeamVacancyResource;
use App\Models\Team;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Traits\CreateDTO;
use App\Traits\SetAdditionalData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TeamVacancyService implements TeamVacancyServiceInterface
{
    use SetAdditionalData;

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

    public function create(int $teamId, CreateTeamVacancyDTO $createTeamVacancyDTO): void
    {
        Gate::authorize(TOUCH_TEAM_VACANCIES_GATE, [Team::class, $teamId]);

        $this->teamVacancyRepository->create($createTeamVacancyDTO);
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyDTO $updateTeamVacancyDTO): void
    {
        Gate::authorize(TOUCH_TEAM_VACANCIES_GATE, [Team::class, $teamVacancy->team_id]);

        $this->teamVacancyRepository->update($teamVacancy, $updateTeamVacancyDTO);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        Gate::authorize(TOUCH_TEAM_VACANCIES_GATE, [Team::class, $teamVacancy->team_id]);

        $this->teamVacancyRepository->delete($teamVacancy);
    }

    protected function assembleVacanciesData(Collection $teamVacancies): Collection
    {
        $this->setCollectionEntityData($teamVacancies, 'team_id', 'teamData', $this->teamRepository);

        return $teamVacancies;
    }
}
