<?php

namespace App\DTO\Team;

use App\Enums\TeamApplicationStatus;

class CreateTeamApplicationDTO
{
    public int $teamId;
    public int $userId;
    public int $vacancyId;
    public string $status = TeamApplicationStatus::Sended;

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

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
}
