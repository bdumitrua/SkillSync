<?php

namespace App\Services\Project;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\AttachEntityData;
use App\Services\Project\Interfaces\ProjectServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectMemberRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectLinkRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\DTO\Project\UpdateProjectDTO;
use App\DTO\Project\CreateProjectMemberDTO;
use App\DTO\Project\CreateProjectDTO;

class ProjectService implements ProjectServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $teamRepository;
    protected $projectRepository;
    protected $projectLinkRepository;
    protected $projectMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        ProjectRepositoryInterface $projectRepository,
        ProjectLinkRepositoryInterface $projectLinkRepository,
        ProjectMemberRepositoryInterface $projectMemberRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->projectRepository = $projectRepository;
        $this->projectLinkRepository = $projectLinkRepository;
        $this->projectMemberRepository = $projectMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $projects = $this->projectRepository->getAll();
        $projects = $this->assembleProjectsData($projects);

        return JsonResource::collection($projects);
    }

    public function show(Project $project): JsonResource
    {
        $project = $this->projectRepository->getById($project->id);
        $project = $this->assembleProjectsData(new Collection([$project]))->first();

        // TODO RESOURCE
        return new JsonResource($project);
    }

    public function search(string $query): JsonResource
    {
        $projects = $this->projectRepository->search($query);
        $projects = $this->assembleProjectsData($projects);

        return JsonResource::collection($projects);
    }

    public function team(Team $team): JsonResource
    {
        $projects = $this->projectRepository->getByTeamId($team->id);
        $projects = $this->assembleProjectsData($projects);

        return JsonResource::collection($projects);
    }

    public function member(User $user): JsonResource
    {
        $projectsIds = $this->projectMemberRepository->getByMemberId($user->id)
            ->pluck('project_id')->toArray();

        $projects = $this->projectRepository->getByIds($projectsIds);
        $projects = $this->assembleProjectsData($projects);

        return JsonResource::collection($projects);
    }

    public function author(User $user): JsonResource
    {
        $projects = $this->projectRepository->getByAuthorId($user->id);
        $projects = $this->assembleProjectsData($projects);

        return JsonResource::collection($projects);
    }

    public function create(CreateProjectDTO $createProjectDTO): void
    {
        Gate::authorize('create', [Project::class, $createProjectDTO]);

        $newProject = $this->projectRepository->create($createProjectDTO);

        if ($newProject->createdByUser()) {
            /** @var CreateProjectMemberDTO */
            $createProjectMemberDTO = new CreateProjectMemberDTO();
            $createProjectMemberDTO->setProjectId($newProject->id)->setUserId($newProject->author_id);

            $this->projectMemberRepository->create($createProjectMemberDTO);
        }
    }

    public function update(Project $project, UpdateProjectDTO $updateProjectDTO): void
    {
        Gate::authorize('update', [Project::class, $project]);

        $this->projectRepository->update($project, $updateProjectDTO);
    }

    public function delete(Project $project): void
    {
        Gate::authorize('delete', [Project::class, $project]);

        $this->projectRepository->delete($project);
    }

    protected function assembleProjectsData(Collection $projects): Collection
    {
        $this->setProjectsAuthorData($projects);
        $this->setProjectsMembersData($projects);
        $this->setProjectsLinks($projects);
        $this->setProjectsRighs($projects);

        return $projects;
    }

    protected function setProjectsAuthorData(Collection &$projects): void
    {
        Log::debug("Setting projects author data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);

        $userIds = [];
        $teamIds = [];

        /** @var Project */
        foreach ($projects as $project) {
            if ($project->createdByUser()) {
                $userIds[] = $project->author_id;
            } elseif ($project->createdByTeam()) {
                $teamIds[] = $project->author_id;
            }
        }

        $userIds = array_unique($userIds);
        $teamIds = array_unique($teamIds);

        $usersData = $this->userRepository->getByIds($userIds);
        $teamsData = $this->teamRepository->getByIds($teamIds);

        /** @var Post */
        foreach ($projects as $project) {
            if ($project->createdByUser()) {
                $project->authorData = $usersData->where('id', $project->author_id)->first();
            } elseif ($project->createdByTeam()) {
                $project->authorData = $teamsData->where('id', $project->author_id)->first();
            }
        }

        Log::debug("Succesfully setted projects author data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);
    }

    protected function setProjectsMembersData(Collection &$projects): void
    {
        $projectsIds = $projects->pluck('id')->toArray();
        $projectsMembers = $this->projectMemberRepository->getByProjectsIds($projectsIds);

        $this->setCollectionEntityData($projectsMembers, 'user_id', 'userData', $this->userRepository);
        $projectsMembers = $projectsMembers->groupBy('project_id');

        foreach ($projects as &$project) {
            $project->members = $projectsMembers->get($project->id) ?? [];
        }
    }

    protected function setProjectsLinks(Collection &$projects): void
    {
        $projectsIds = $projects->pluck('id')->toArray();
        $projectsLinks = $this->projectLinkRepository->getByProjectsIds($projectsIds)->groupBy('project_id');

        foreach ($projects as &$project) {
            $project->links = $projectsLinks->get($project->id) ?? [];
        }
    }

    protected function setProjectsRighs(Collection &$projects): void
    {
        foreach ($projects as &$project) {
            // TODO LIKES
            $project->isLiked = false;
            $project->canUpdate = Gate::allows('update', [Project::class, $project]);
            $project->canDelete = Gate::allows('delete', [Project::class, $project]);;
        }
    }
}
