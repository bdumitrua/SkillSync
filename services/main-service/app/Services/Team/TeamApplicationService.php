<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamApplicationDTO;
use App\Enums\TeamApplicationStatus;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\TeamVacancy;
use App\Models\TeamApplication;
use App\Http\Resources\Team\TeamApplicationResource;
use App\Models\Team;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\SetAdditionalData;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class TeamApplicationService implements TeamApplicationServiceInterface
{
    use SetAdditionalData;

    protected $userRepository;
    protected $teamRepository;
    protected $vacancyRepository;
    protected $teamApplicationRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        TeamVacancyRepositoryInterface $vacancyRepository,
        TeamApplicationRepositoryInterface $teamApplicationRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->vacancyRepository = $vacancyRepository;
        $this->teamApplicationRepository = $teamApplicationRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function show(int $teamApplicationId): JsonResource
    {
        $teamApplication = $this->teamApplicationRepository->getById($teamApplicationId);

        if (Gate::denies(VIEW_TEAM_APPLICATIONS_GATE, [TeamApplication::class, $teamApplication])) {
            return new JsonResource([]);
        }

        $teamApplication = $this->assembleApplicationsData(new Collection([$teamApplication]))->first();
        $teamApplication->canUpdate = Gate::authorize(UPDATE_TEAM_APPLICATION_GATE, [TeamApplication::class, $teamApplication->team_id]);
        $teamApplication->canDelete = Gate::authorize(DELETE_TEAM_APPLICATION_GATE, [TeamApplication::class, $teamApplication]);

        return new TeamApplicationResource($teamApplication);
    }

    public function team(int $teamId): JsonResource
    {
        if (Gate::denies(MONITOR_TEAM_APPLICATIONS_GATE, [Team::class, $teamId])) {
            return new JsonResource([]);
        }

        $teamApplications = $this->teamApplicationRepository->getByTeamId($teamId);
        $teamApplications = $this->assembleApplicationsData($teamApplications);
        $teamApplications = $this->setApplicationsRights($teamApplications);

        return TeamApplicationResource::collection($teamApplications);
    }

    public function vacancy(TeamVacancy $teamVacancy): JsonResource
    {
        if (Gate::denies(MONITOR_TEAM_APPLICATIONS_GATE, [Team::class, $teamVacancy->team_id])) {
            return new JsonResource([]);
        }

        $teamApplications = $this->teamApplicationRepository->getByVacancyId($teamVacancy->id);
        $teamApplications = $this->assembleApplicationsData($teamApplications);
        $teamApplications = $this->setApplicationsRights($teamApplications);

        return TeamApplicationResource::collection($teamApplications);
    }

    public function create(CreateTeamApplicationDTO $createTeamApplicationDTO): void
    {
        Gate::authorize(APPLY_TO_VACANCY_GATE, [
            TeamApplication::class,
            $createTeamApplicationDTO->teamId,
            $createTeamApplicationDTO->vacancyId
        ]);

        $this->teamApplicationRepository->create($createTeamApplicationDTO);
    }

    public function update(TeamApplication $teamApplication, string $newStatus): void
    {
        Gate::authorize(UPDATE_TEAM_APPLICATION_GATE, [TeamApplication::class, $teamApplication->team_id]);

        $this->teamApplicationRepository->update($teamApplication, $newStatus);
    }

    public function delete(TeamApplication $teamApplication): void
    {
        Gate::authorize(DELETE_TEAM_APPLICATION_GATE, [TeamApplication::class, $teamApplication]);

        $this->teamApplicationRepository->delete($teamApplication);
    }

    protected function assembleApplicationsData(Collection $teamApplications): Collection
    {
        $this->setCollectionEntityData($teamApplications, 'user_id', 'userData', $this->userRepository);
        $this->setCollectionEntityData($teamApplications, 'vacancy_id', 'vacancyData', $this->vacancyRepository);

        return $teamApplications;
    }

    protected function setApplicationsRights(Collection $teamApplications): Collection
    {
        $canUpdate = Gate::authorize(UPDATE_TEAM_APPLICATION_GATE, [TeamApplication::class, $teamApplications->first()?->team_id]);
        $canDelete = false; // If you're watching a list of applications - you're a member = you can't apply = you can't delete

        foreach ($teamApplications as $teamApplication) {
            $teamApplication->canUpdate = $canUpdate;
            $teamApplication->canDelete = $canDelete;
        }

        return $teamApplications;
    }
}
