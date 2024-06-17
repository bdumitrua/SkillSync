<?php

namespace App\Services\Team;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Team\Interfaces\TeamVacancyServiceInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Models\TeamVacancy;
use App\Models\Team;
use App\Http\Resources\Team\TeamVacancyResource;
use App\DTO\Team\UpdateTeamVacancyDTO;
use App\DTO\Team\CreateTeamVacancyDTO;

class TeamVacancyService implements TeamVacancyServiceInterface
{
    use AttachEntityData;

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

        return TeamVacancyResource::collection($teamVacancies);
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
        $this->assembleVacanciesRights($teamVacancies);

        return $teamVacancies;
    }

    protected function assembleVacanciesRights(Collection &$teamVacancies): void
    {
        if (empty($teamVacancies->toArray())) {
            return;
        }

        // All vacancies should be from one team
        $canTouchTeamVacancies = Gate::allows(TOUCH_TEAM_VACANCIES_GATE, [Team::class, $teamVacancies->first()?->team_id]);

        foreach ($teamVacancies as $vacancy) {
            $vacancy->canChange = $canTouchTeamVacancies;
        }
    }
}
