<?php

namespace App\DTO\Team;

class CreateTeamMemberDTO
{
    public string $userId;
    public string $teamId;
    public bool $isModerator;

    public ?string $about = null;

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'team_id' => $this->teamId,
            'is_moderator' => $this->isModerator,
            'about' => $this->about,
        ];
    }
}
