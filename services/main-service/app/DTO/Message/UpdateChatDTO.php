<?php

namespace App\DTO\Message;

class UpdateChatDTO
{
    public string $name;

    public ?string $avatarUrl = null;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'avatarUrl' => $this->avatarUrl,
        ];
    }
}
