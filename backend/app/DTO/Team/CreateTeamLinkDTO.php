<?php

namespace App\DTO\Team;

class CreateTeamLinkDTO
{
    public int $teamId = 0;
    public bool $isPrivate;
    public string $url;

    public ?string $text = null;
    public ?string $iconType = null;

    public function toArray(): array
    {
        return [
            'team_id' => $this->teamId,
            'is_private' => $this->isPrivate,
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }

    public function setTeamId(int $teamId): self
    {
        $this->teamId = $teamId;
        return $this;
    }
}
