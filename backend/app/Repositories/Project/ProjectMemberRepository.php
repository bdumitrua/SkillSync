<?php

namespace App\Repositories\Project;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use App\Traits\Updateable;
use App\Traits\Cacheable;
use App\Repositories\Project\Interfaces\ProjectMemberRepositoryInterface;
use App\Models\ProjectMember;
use App\DTO\Project\UpdateProjectMemberDTO;
use App\DTO\Project\CreateProjectMemberDTO;

class ProjectMemberRepository implements ProjectMemberRepositoryInterface
{
    use Updateable, Cacheable;

    public function getByProjectId(int $projectId): Collection
    {
        $cacheKey = $this->getProjectMembersCacheKey($projectId);
        return $this->getCachedData($cacheKey, CACHE_TIME_PROJECT_MEMBERS_DATA, function () use ($projectId) {
            return ProjectMember::where('project_id', '=', $projectId)->get();
        });
    }

    public function getByProjectsIds(array $projectsIds): Collection
    {
        return $this->getCachedCollection($projectsIds, function ($projectId) {
            return $this->getByProjectId($projectId);
        });
    }

    public function getByMemberId(int $memberId): Collection
    {
        return ProjectMember::where('user_id', '=', $memberId)->get();
    }

    public function getMemberByBothIds(int $projectId, int $userId): ?ProjectMember
    {
        return ProjectMember::where('project_id', '=', $projectId)
            ->where('user_id', '=', $userId)
            ->first();
    }

    public function create(CreateProjectMemberDTO $createProjectMemberDTO): void
    {
        Log::debug('Creating project member from dto', [
            'createProjectMemberDTO' => $createProjectMemberDTO->toArray()
        ]);

        ProjectMember::create(
            $createProjectMemberDTO->toArray()
        );
    }

    public function update(ProjectMember $projectMember, UpdateProjectMemberDTO $updateProjectMemberDTO): void
    {
        Log::debug('Updating project member from dto', [
            'updateProjectMemberDTO' => $updateProjectMemberDTO->toArray()
        ]);

        $this->updateFromDto($projectMember, $updateProjectMemberDTO);
    }

    public function delete(ProjectMember $projectMember): void
    {
        Log::debug('Deleting projectMember', [
            'projectMember' => $projectMember->toArray(),
        ]);

        $projectMember->delete();
    }

    protected function getProjectMembersCacheKey(int $projectId): string
    {
        return CACHE_KEY_PROJECT_MEMBERS_DATA . $projectId;
    }
}
