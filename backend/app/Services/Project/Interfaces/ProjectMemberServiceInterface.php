<?php

namespace App\Services\Project\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Project;
use App\DTO\Project\UpdateProjectMemberDTO;
use App\DTO\Project\CreateProjectMemberDTO;

interface ProjectMemberServiceInterface
{
    /**
     * @param Project $project
     * 
     * @return JsonResource
     */
    public function project(Project $project): JsonResource;

    /**
     * @param Project $project
     * @param User $user
     * @param CreateProjectMemberDTO $createDTO
     * 
     * @return void
     */
    public function create(Project $project, User $user, CreateProjectMemberDTO $createDTO): void;

    /**
     * @param Project $project
     * @param User $user
     * @param UpdateProjectMemberDTO $updateDTO
     * 
     * @return void
     */
    public function update(Project $project, User $user, UpdateProjectMemberDTO $updateDTO): void;

    /**
     * @param Project $project
     * @param User $user
     * 
     * @return void
     */
    public function delete(Project $project, User $user): void;
}
