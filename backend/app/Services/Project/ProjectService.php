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
use App\Repositories\Interfaces\LikeRepositoryInterface;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Http\Resources\Project\ProjectResource;
use App\DTO\Project\UpdateProjectDTO;
use App\DTO\Project\CreateProjectMemberDTO;
use App\DTO\Project\CreateProjectDTO;

class ProjectService implements ProjectServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $teamRepository;
    protected $likeRepository;
    protected $projectRepository;
    protected $projectLinkRepository;
    protected $projectMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        LikeRepositoryInterface $likeRepository,
        ProjectRepositoryInterface $projectRepository,
        ProjectLinkRepositoryInterface $projectLinkRepository,
        ProjectMemberRepositoryInterface $projectMemberRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->likeRepository = $likeRepository;
        $this->projectRepository = $projectRepository;
        $this->projectLinkRepository = $projectLinkRepository;
        $this->projectMemberRepository = $projectMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $projects = $this->projectRepository->getAll();
        $projects = $this->assembleProjectsData($projects);

        return ProjectResource::collection($projects);
    }

    public function show(Project $project): JsonResource
    {
        $project = $this->projectRepository->getById($project->id);
        $project = $this->assembleProjectsData(new Collection([$project]))->first();

        return new ProjectResource($project);
    }

    public function search(string $query): JsonResource
    {
        $projects = $this->projectRepository->search($query);
        $projects = $this->assembleProjectsData($projects);

        return ProjectResource::collection($projects);
    }

    public function team(Team $team): JsonResource
    {
        $projects = $this->projectRepository->getByTeamId($team->id);
        $projects = $this->assembleProjectsData($projects);

        return ProjectResource::collection($projects);
    }

    public function member(User $user): JsonResource
    {
        $projectsIds = $this->projectMemberRepository->getByMemberId($user->id)
            ->pluck('project_id')->toArray();

        $projects = $this->projectRepository->getByIds($projectsIds);
        $projects = $this->assembleProjectsData($projects);

        return ProjectResource::collection($projects);
    }

    public function author(User $user): JsonResource
    {
        $projects = $this->projectRepository->getByAuthorId($user->id);
        $projects = $this->assembleProjectsData($projects);

        return ProjectResource::collection($projects);
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

        $this->setCollectionMorphData($projects, 'author', 'user', $this->userRepository);
        $this->setCollectionMorphData($projects, 'author', 'team', $this->teamRepository);

        Log::debug("Successfully set projects author data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);
    }

    protected function setProjectsMembersData(Collection &$projects): void
    {
        Log::debug("Setting projects members data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);

        $projectsIds = $projects->pluck('id')->toArray();
        $projectsMembers = $this->projectMemberRepository->getByProjectsIds($projectsIds);

        /** @var Collection $projectMembers */
        foreach ($projectsMembers as $projectMembers) {
            $this->setCollectionEntityData($projectMembers, 'user_id', 'userData', $this->userRepository);
            $projectMembers = $projectMembers->groupBy('project_id');
        }

        foreach ($projects as &$project) {
            $project->membersData = $projectMembers->get($project->id) ?? [];
        }

        Log::debug("Successfully set projects members data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);
    }

    protected function setProjectsLinks(Collection &$projects): void
    {
        Log::debug("Setting projects links data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);

        $projectsIds = $projects->pluck('id')->toArray();
        $projectsLinks = $this->projectLinkRepository->getByProjectsIds($projectsIds)->groupBy('project_id');

        foreach ($projects as &$project) {
            $project->linksData = $projectsLinks->get($project->id) ?? [];
        }

        Log::debug("Successfully set projects links data", [
            'projects' => $projects->pluck('id')->toArray(),
        ]);
    }

    // TODO NE NRAVITSA)
    protected function setProjectsRighs(Collection &$projects): void
    {
        $projectsIds = $projects->pluck('id')->toArray();
        $projectsLikes = $this->likeRepository->getByUserAndProjectsIds($this->authorizedUserId, $projectsIds);

        foreach ($projects as $project) {
            $project->isLiked = $projectsLikes->contains('likeable_id', $project->id);
            $project->canUpdate = Gate::allows('update', [Project::class, $project]);
            $project->canDelete = Gate::allows('delete', [Project::class, $project]);;
        }
    }
}
