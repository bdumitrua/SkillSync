<?php

namespace App\DTO\Team;

use App\Enums\TeamApplicationStatus;

class CreateTeamApplicationDTO
{
    public int $teamId = 0;
    public int $userId = 0;
    public int $vacancyId = 0;
    public string $status = TeamApplicationStatus::Sended->value;

    public ?string $text = null;

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

    public function setTeamId(int $teamId): self
    {
        $this->teamId = $teamId;
        return $this;
    }

    public function setVacancyId(int $vacancyId): self
    {
        $this->vacancyId = $vacancyId;
        return $this;
    }
}
