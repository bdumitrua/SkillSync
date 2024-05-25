<?php

namespace App\DTO\Team;

class CreateTeamLinkDTO
{
    public string $name;
    public int $teamId;
    public bool $isPrivate;
    public string $url;

    public ?string $text;
    public ?string $iconType;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'team_id' => $this->teamId,
            'is_private' => $this->isPrivate,
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }
}
