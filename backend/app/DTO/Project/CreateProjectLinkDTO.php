<?php

namespace App\DTO\Project;

class CreateProjectLinkDTO
{
    public int $projectId = 0;
    public string $url;

    public ?string $text = null;
    public ?string $iconType = null;

    public function toArray(): array
    {
        return [
            'project_id' => $this->projectId,
            'url' => $this->url,
            'text' => $this->text,
            'icon_type' => $this->iconType,
        ];
    }

    public function setProjectId(int $projectId): self
    {
        $this->projectId = $projectId;
        return $this;
    }
}
