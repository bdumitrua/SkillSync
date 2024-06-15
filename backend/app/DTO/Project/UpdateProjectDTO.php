<?php

namespace App\DTO\Project;

class UpdateProjectDTO
{
    public string $name;

    public ?string $description = null;
    public ?string $coverUrl = null;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'cover_url' => $this->coverUrl,
        ];
    }
}
