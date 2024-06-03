<?php

namespace App\DTO\Team;

class CreateTeamVacancyDTO
{
    public int $teamId;
    public string $title;
    public string $description;

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function setTeamId(int $teamId): void
    {
        $this->teamId = $teamId;
    }
}
