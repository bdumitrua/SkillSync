<?php

namespace App\Repositories\Project\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\ProjectLink;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

interface ProjectLinkRepositoryInterface
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
     * @param CreateProjectLinkDTO $createProjectLinkDTO
     * 
     * @return void
     */
    public function create(CreateProjectLinkDTO $createProjectLinkDTO): void;

    /**
     * @param ProjectLink $projectLink
     * @param UpdateProjectLinkDTO $updateProjectLinkDTO
     * 
     * @return void
     */
    public function update(ProjectLink $projectLink, UpdateProjectLinkDTO $updateProjectLinkDTO): void;

    /**
     * @param ProjectLink $projectLink
     * 
     * @return void
     */
    public function delete(ProjectLink $projectLink): void;
}
