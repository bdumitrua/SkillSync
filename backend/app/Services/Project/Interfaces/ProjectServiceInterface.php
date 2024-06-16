<?php

namespace App\Services\Project\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Requests\Project\CreateProjectRequest;
use App\DTO\Project\UpdateProjectDTO;
use App\DTO\Project\CreateProjectDTO;

interface ProjectServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @param Project $project
     * 
     * @return JsonResource
     */
    public function show(Project $project): JsonResource;

    /**
     * @param Team $team
     * 
     * @return JsonResource
     */
    public function team(Team $team): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function member(User $user): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function author(User $user): JsonResource;

    /**
     * @param CreateProjectDTO $createProjectDTO
     * 
     * @return void
     */
    public function create(CreateProjectDTO $createProjectDTO): void;

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
