<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authorData = !empty($this->authorData)
            ? (new UserDataResource($this->authorData))->resolve()
            : [];

        $membersData = !empty($this->membersData)
            ? (ProjectMemberResource::collection($this->membersData))->resolve()
            : [];

        $linksData = !empty($this->linksData)
            ? (ProjectLinkResource::collection($this->linksData))->resolve()
            : [];

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

        ];
    }
}
