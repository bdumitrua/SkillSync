<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\ActionsResource;

class TeamApplicationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userData = (new UserDataResource($this->userData))->resolve();
        $vacancyData = (new TeamVacancyResource($this->vacancyData))->resolve();

        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'text' => $this->text,
            'status' => $this->status,
            'userId' => $this->user_id,
            'userData' => $userData,
            'vacancyId' => $this->vacancy_id,
            'vacancyData' => $vacancyData,
            'teamId' => $this->team_id,
            'canUpdate' => $this->canUpdate,
            'canDelete' => $this->canDelete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'actions' => $actions
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->canDelete) {
            $actions[] = [
                "DeleteTeamApplication",
                "teams.applications.delete",
                ["teamApplication" => $this->id]
            ];
        }

        return (array) ActionsResource::collection($actions);
    }
}
