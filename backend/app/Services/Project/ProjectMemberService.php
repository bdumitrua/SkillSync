<?php

namespace App\Services\Project;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Traits\AttachEntityData;
use App\Services\Project\Interfaces\ProjectMemberServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectMemberRepositoryInterface;
use App\Models\User;
use App\Models\ProjectMember;
use App\Models\Project;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Project\ProjectMemberResource;
use App\DTO\Project\UpdateProjectMemberDTO;
use App\DTO\Project\CreateProjectMemberDTO;

class ProjectMemberService implements ProjectMemberServiceInterface
{
    use AttachEntityData;

    protected $userRepository;
    protected $projectMemberRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        UserRepositoryInterface $userRepository,
        ProjectMemberRepositoryInterface $projectMemberRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->projectMemberRepository = $projectMemberRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function project(Project $project): JsonResource
    {
        $members = $this->projectMemberRepository->getByProjectId($project->id);
        $this->setCollectionEntityData($members, 'user_id', 'userData', $this->userRepository);

        return ProjectMemberResource::collection($members);
    }

    public function create(Project $project, User $user, CreateProjectMemberDTO $createDTO): void
    {
        $projectMember = $this->projectMemberRepository->getMemberByBothIds($project->id, $user->id);
        Gate::authorize('create', [ProjectMember::class, $project, $projectMember]);

        $this->projectMemberRepository->create($createDTO);
    }

    public function update(Project $project, User $user, UpdateProjectMemberDTO $updateDTO): void
    {
        $projectMember = $this->projectMemberRepository->getMemberByBothIds($project->id, $user->id);
        Gate::authorize('update', [ProjectMember::class, $project, $projectMember]);

        $this->projectMemberRepository->update($projectMember, $updateDTO);
    }

    public function delete(Project $project, User $user): void
    {
        $projectMember = $this->projectMemberRepository->getMemberByBothIds($project->id, $user->id);
        Gate::authorize('delete', [ProjectMember::class, $project, $projectMember]);

        $this->projectMemberRepository->delete($projectMember);
    }
}
