<?php

namespace App\DTO\Project;

class UpdateProjectLinkDTO
{
    public string $url;

    public ?string $text = null;
    public ?string $iconType = null;

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }
}
