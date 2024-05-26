<?php

namespace App\Services\Team;

use App\DTO\Team\CreateTeamApplicationDTO;
use App\Services\Team\Interfaces\TeamApplicationServiceInterface;
use App\Repositories\Team\Interfaces\TeamApplicationRepositoryInterface;
use App\Models\TeamVacancy;
use App\Models\TeamApplication;
use App\Http\Requests\Team\UpdateTeamApplicationRequest;
use App\Http\Requests\Team\CreateTeamApplicationRequest;
use App\Http\Resources\Team\TeamApplicationResource;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamVacancyRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\CreateDTO;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TeamApplicationService implements TeamApplicationServiceInterface
{
    use CreateDTO;

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

        if (Gate::denies('viewTeamApplication', $teamApplication)) {
            return new JsonResource([]);
        }

        $teamApplication = $this->assembleApplicationsData(new Collection([$teamApplication]))->first();

        return new TeamApplicationResource($teamApplication);
    }

    public function team(int $teamId): JsonResource
    {
        if (Gate::denies('moderator', $teamId)) {
            return new JsonResource([]);
        }

        $teamApplications = $this->teamApplicationRepository->getByTeamId($teamId);
        $teamApplications = $this->assembleApplicationsData($teamApplications);

        return TeamApplicationResource::collection($teamApplications);
    }

    public function vacancy(TeamVacancy $teamVacancy): JsonResource
    {
        if (!Gate::allows('moderator', $teamVacancy->team_id)) {
            return new JsonResource([]);
        }

        $teamApplications = $this->teamApplicationRepository->getByVacancyId($teamVacancy->id);
        $teamApplications = $this->assembleApplicationsData($teamApplications);

        return TeamApplicationResource::collection($teamApplications);
    }

    public function create(CreateTeamApplicationRequest $request): void
    {
        /** @var CreateTeamApplicationDTO */
        $createApplicationDTO = $this->createDTO($request, CreateTeamApplicationDTO::class);
        $createApplicationDTO->userId = $this->authorizedUserId;

        if (Gate::allows('member', $createApplicationDTO->userId)) {
            return;
        }

        $alreadyApplied = $this->teamApplicationRepository->userAppliedToVacancy(
            $this->authorizedUserId,
            $createApplicationDTO->vacancyId
        );

        if ($alreadyApplied) {
            return;
        }

        $this->teamApplicationRepository->create($createApplicationDTO);
    }

    public function update(TeamApplication $teamApplication, UpdateTeamApplicationRequest $request): void
    {
        Gate::authorize('moderator', $teamApplication->team_id);

        $this->teamApplicationRepository->update($teamApplication, $request->status);
    }

    public function delete(TeamApplication $teamApplication): void
    {
        Gate::authorize('deleteTeamApplication', $teamApplication);

        $this->teamApplicationRepository->delete($teamApplication);
    }

    protected function assembleApplicationsData(Collection $teamApplications): Collection
    {
        $this->setApplicationsUserData($teamApplications);
        $this->setApplicationsVacancyData($teamApplications);

        return $teamApplications;
    }

    // TODO REFACTOR
    protected function setApplicationsUserData(Collection &$teamApplications): void
    {
        $userIds = $teamApplications->pluck('user_id')->unique()->all();
        $usersData = $this->userRepository->getByIds($userIds);

        foreach ($teamApplications as $application) {
            $application->userData = $usersData->where('id', $application->user_id)->first();
        }
    }

    protected function setApplicationsVacancyData(Collection &$teamApplications): void
    {
        $vacancyIds = $teamApplications->pluck('vacancy_id')->unique()->all();
        $vacanciesData = $this->vacancyRepository->getByIds($vacancyIds);

        foreach ($teamApplications as $application) {
            $application->vacancyData = $vacanciesData->where('id', $application->vacancy_id)->first();
        }
    }

    protected function setApplicationsTeamData(Collection &$teamApplications): void
    {
        $teamIds = $teamApplications->pluck('team_id')->unique()->all();
        $teamsData = $this->teamRepository->getByIds($teamIds);

        foreach ($teamApplications as $application) {
            $application->teamData = $teamsData->where('id', $application->team_id)->first();
        }
    }
}
