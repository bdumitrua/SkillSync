<?php

namespace App\DTO\Team;

class CreateTeamMemberDTO
{
    public string $userId;
    public bool $isModerator;

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'is_moderator' => $this->isModerator,
        ];
    }
}
