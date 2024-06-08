<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\ActionsResource;
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

        $actions = $this->prepareActions();

        return [
            'title' => $this->title,
            'description' => $this->description,
            'teamId' => $this->team_id,
            'teamData' => $teamData,
            'canChange' => $this->canChange,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'actions' => $actions,
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->canChange) {
            $actions[] = [
                "DeleteTeamVacancy",
                "teams.vacancies.delete",
                ["teamVacancy" => $this->id]
            ];
        }

        return (array) ActionsResource::collection($actions);
    }
}
