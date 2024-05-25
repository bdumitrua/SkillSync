<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'url' => $this->url,
            'text' => $this->text,
            'iconType' => $this->icon_type,
            'isPrivate' => $this->is_private,
        ];
    }
}
