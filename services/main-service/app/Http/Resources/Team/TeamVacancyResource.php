<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamVacancyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $teamData = $this->teamData
            ? (new TeamDataResource($this->teamData))->resolve()
            : [];

        return [
            'title' => $this->title,
            'description' => $this->description,
            'teamId' => $this->team_id,
            'teamData' => $teamData,
        ];
    }
}
