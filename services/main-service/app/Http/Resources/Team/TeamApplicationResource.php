<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'userId' => $this->user_id,
            // TODO USER DATA
            'userData' => [],
            'vacancyId' => $this->vacancy_id,
            // TODO VACANCY DATA
            'vacancyData' => [],
            'teamId' => $this->team_id,
            'text' => $this->text,
            'status' => $this->status,
        ];
    }
}
