<?php

namespace App\Http\Resources\Project;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class ProjectLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "projectId" => $this->project_id,
            "url" => $this->url,
            "text" => $this->text,
            "iconType" => $this->icon_type,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
