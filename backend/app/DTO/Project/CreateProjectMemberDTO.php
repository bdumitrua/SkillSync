<?php

namespace App\DTO\Project;

class CreateProjectMemberDTO
{
    public int $projectId = 0;
    public int $userId = 0;
    public string $additional = '';

    public function toArray(): array
    {
        return [
            'project_id' => $this->projectId,
            'user_id' => $this->userId,
            'additional' => $this->additional,
        ];
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setAdditional(int $additional): self
    {
        $this->additional = $additional;
        return $this;
    }
}
