<?php

namespace App\DTO\Team;

class UpdateTeamLinkDTO
{
    public string $name;
    public string $url;
    public bool $isPrivate;

    public ?string $text;
    public ?string $iconType;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'is_private' => $this->isPrivate,
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }
}
