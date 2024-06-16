<?php

namespace App\Repositories\Project\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\ProjectMember;
use App\DTO\Project\UpdateProjectMemberDTO;
use App\DTO\Project\CreateProjectMemberDTO;

interface ProjectMemberRepositoryInterface
{
    /**
     * @param int $projectId
     * 
     * @return Collection
     */
    public function getByProjectId(int $projectId): Collection;

    /**
     * @param array $projectsIds
     * 
     * @return Collection
     */
    public function getByProjectsIds(array $projectsIds): Collection;

    /**
     * @param int $memberId
     * 
     * @return Collection
     */
    public function getByMemberId(int $memberId): Collection;

    /**
     * @param int $projectId
     * @param int $userId
     * 
     * @return ProjectMember|null
     */
    public function getMemberByBothIds(int $projectId, int $userId): ?ProjectMember;

    /**
     * @param CreateProjectMemberDTO $createProjectMemberDTO
     * 
     * @return void
     */
    public function create(CreateProjectMemberDTO $createProjectMemberDTO): void;

    /**
     * @param ProjectMember $projectMember
     * @param UpdateProjectMemberDTO $updateProjectMemberDTO
     * 
     * @return void
     */
    public function update(ProjectMember $projectMember, UpdateProjectMemberDTO $updateProjectMemberDTO): void;

    /**
     * @param ProjectMember $projectMember
     * 
     * @return void
     */
    public function delete(ProjectMember $projectMember): void;
}
