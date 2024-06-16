<?php

namespace App\Repositories\Project\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Project;
use App\DTO\Project\UpdateProjectDTO;
use App\DTO\Project\CreateProjectDTO;

interface ProjectRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getAll(): Collection;

    /**
     * @param int $projectId
     * 
     * @return Project|null
     */
    public function getById(int $projectId): ?Project;

    /**
     * @param array $projectsIds
     * 
     * @return Collection
     */
    public function getByIds(array $projectsIds): Collection;

    /**
     * @param int $authorId
     * 
     * @return Collection
     */
    public function getByAuthorId(int $authorId): Collection;

    /**
     * @param int $teamId
     * 
     * @return Collection
     */
    public function getByTeamId(int $teamId): Collection;

    /**
     * @param CreateProjectDTO $createProjectDTO
     * 
     * @return Project
     */
    public function create(CreateProjectDTO $createProjectDTO): Project;

    /**
     * @param Project $project
     * @param UpdateProjectDTO $updateProjectDTO
     * 
     * @return void
     */
    public function update(Project $project, UpdateProjectDTO $updateProjectDTO): void;

    /**
     * @param Project $project
     * 
     * @return void
     */
    public function delete(Project $project): void;
}
