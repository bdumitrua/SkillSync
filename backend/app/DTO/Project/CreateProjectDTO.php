<?php

namespace App\DTO\Project;

class CreateProjectDTO
{
    public string $authorType;
    public int $authorId;
    public string $name;

    public ?string $description = null;
    public ?string $coverUrl = null;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'author_type' => $this->authorType,
            'author_id' => $this->authorId,
            'cover_url' => $this->coverUrl,
        ];
    }
}
