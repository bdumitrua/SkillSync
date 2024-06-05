<?php

namespace App\DTO\Post;

class CreatePostDTO
{
    public string $text;
    public string $entityType;
    public int $entityId;

    public ?string $mediaUrl = null;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
            'media_url' => $this->mediaUrl,
        ];
    }
}
