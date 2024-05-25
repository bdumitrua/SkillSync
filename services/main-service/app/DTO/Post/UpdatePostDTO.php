<?php

namespace App\DTO\Post;

class CreatePostDTO
{
    public string $text;

    public ?string $mediaUrl;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'media_url' => $this->mediaUrl,
        ];
    }
}
