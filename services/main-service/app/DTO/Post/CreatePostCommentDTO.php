<?php

namespace App\DTO\Post;

class CreatePostCommentDTO
{
    public string $text;
    public int $userId;
    public int $postId;
    public ?string $mediaUrl;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'user_id' => $this->userId,
            'post_id' => $this->postId,
            'media_url' => $this->mediaUrl,
        ];
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setPostId(int $postId): self
    {
        $this->postId = $postId;
        return $this;
    }
}
