<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\ActionsResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authorData = $this->authorData ?? $this->author ?? null;
        if (!empty($authorData)) {
            if ($this->author_type === config('entities.user')) {
                $authorData = (new UserDataResource($authorData));
            } elseif ($this->author_type === config('entities.team')) {
                $authorData = (new TeamDataResource($authorData));
            }
        }

        $membersData = !empty($this->membersData)
            ? (ProjectMemberResource::collection($this->membersData))->resolve()
            : [];

        $linksData = !empty($this->linksData)
            ? (ProjectLinkResource::collection($this->linksData))->resolve()
            : [];

        $actions = $this->prepareActions();

        return [
            "id" => $this->id,
            "authorType" => $this->author_type,
            "authorId" => $this->author_id,
            "name" => $this->name,
            "description" => $this->description,
            "coverUrl" => $this->cover_url,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "likesCount" => $this->likes_count,
            "authorData" => $authorData,
            "membersData" => $membersData,
            "linksData" => $linksData,
            'isLiked' => $this->isLiked,
            'actions' => $actions
        ];
    }

    private function prepareActions(): array
    {
        $actions = [
            [
                "DeleteProject",
                "projects.delete",
                ["project" => $this->id]
            ],
        ];

        return (array) ActionsResource::collection($actions);
    }
}
