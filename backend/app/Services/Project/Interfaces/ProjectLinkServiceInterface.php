<?php

namespace App\Services\Project\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\ProjectLink;
use App\Models\Project;
use App\DTO\Project\UpdateProjectLinkDTO;
use App\DTO\Project\CreateProjectLinkDTO;

interface ProjectLinkServiceInterface
{
    /**
     * @param Project $project
     * 
     * @return JsonResource
     */
    public function project(Project $project): JsonResource;

    /**
     * @param Project $project
     * @param CreateProjectLinkDTO $createProjectLinkDTO
     * 
     * @return void
     */
    public function create(Project $project, CreateProjectLinkDTO $createProjectLinkDTO): void;

    /**
     * @param Project $project
     * @param ProjectLink $projectLink
     * @param UpdateProjectLinkDTO $updateProjectLinkDTO
     * 
     * @return void
     */
    public function update(Project $project, ProjectLink $projectLink, UpdateProjectLinkDTO $updateProjectLinkDTO): void;

    /**
     * @param Project $project
     * @param ProjectLink $projectLink
     * 
     * @return void
     */
    public function delete(Project $project, ProjectLink $projectLink): void;
}
