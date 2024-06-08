<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class TeamDataResource extends JsonResource
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
            'avatar' => $this->avatar,
            'description' => $this->description,
        ];
    }
}
