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
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Traits\CreateDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class TeamVacancyService implements TeamVacancyServiceInterface
{
    use CreateDTO;

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
        Gate::authorize('moderator', $teamId);

        /** @var CreateTeamVacancyDTO */
        $createTeamVacancyDTO = $this->createDTO($request, CreateTeamVacancyDTO::class);
        $createTeamVacancyDTO->teamId = $teamId;

        $this->teamVacancyRepository->create($createTeamVacancyDTO);
    }

    public function update(TeamVacancy $teamVacancy, UpdateTeamVacancyRequest $request): void
    {
        Gate::authorize('moderator', $teamVacancy->team_id);

        $updateTeamVacancyDTO = $this->createDTO($request, UpdateTeamVacancyDTO::class);

        $this->teamVacancyRepository->update($teamVacancy, $updateTeamVacancyDTO);
    }

    public function delete(TeamVacancy $teamVacancy): void
    {
        Gate::authorize('moderator', $teamVacancy->team_id);

        $this->teamVacancyRepository->delete($teamVacancy);
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
