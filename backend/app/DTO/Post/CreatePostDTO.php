<?php

namespace App\DTO\Post;

class CreatePostDTO
{
    public string $text;
    public string $authorType;
    public int $authorId;

    public ?string $mediaUrl = null;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'author_type' => $this->authorType,
            'author_id' => $this->authorId,
            'media_url' => $this->mediaUrl,
        ];
    }
}
