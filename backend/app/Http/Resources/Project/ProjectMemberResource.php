<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;

class ProjectMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userData = !empty($this->userData)
            ? (new UserDataResource($this->userData))->resolve()
            : [];

        return [
            "id" => $this->id,
            "userId" => $this->user_id,
            'userData' => $userData,
            "projectId" => $this->project_id,
            "additional" => $this->additional,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
