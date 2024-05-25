<?php

namespace App\DTO\Post;

class CreatePostDTO
{
    public string $text;
    public string $entityType;
    public int $entityId;

    public ?string $mediaUrl;

    public function toArray(): array
    {
        // TODO change type to class-name
        return [
            'text' => $this->text,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
            'media_url' => $this->mediaUrl,
        ];
    }
}
