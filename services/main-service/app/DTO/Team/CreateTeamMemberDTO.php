<?php

namespace App\DTO\Team;

class CreateTeamMemberDTO
{
    public int $userId;
    public int $teamId;
    public bool $isModerator;

    public function __construct(int $userId, int $teamId, bool $isModerator)
    {
        $this->userId = $userId;
        $this->teamId = $teamId;
        $this->isModerator = $isModerator;
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'team_id' => $this->teamId,
            'is_moderator' => $this->isModerator,
        ];
    }
}
