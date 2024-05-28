<?php

namespace App\DTO\Team;

class UpdateTeamMemberDTO
{
    public bool $isModerator;

    public ?string $about = null;

    public function toArray(): array
    {
        return [
            'is_moderator' => $this->isModerator,
            'about' => $this->about,
        ];
    }
}
