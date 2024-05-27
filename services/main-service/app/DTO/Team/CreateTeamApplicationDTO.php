<?php

namespace App\DTO\Team;

class CreateTeamApplicationDTO
{
    public int $teamId;
    public int $userId;
    public int $vacancyId;
    public string $status;

    public ?string $text;

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'user_id' => $this->userId,
            'vacancy_id' => $this->vacancyId,
            'status' => $this->status,
            'text' => $this->text,
        ];
    }
}
