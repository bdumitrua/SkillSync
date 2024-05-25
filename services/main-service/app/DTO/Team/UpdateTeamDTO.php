<?php

namespace App\DTO\Team;

class UpdateTeamDTO
{
    public string $name;

    public ?string $avatar = null;
    public ?string $description = null;
    public ?string $email = null;
    public ?string $site = null;
    public ?int $chatId = null;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'chat_id' => $this->chatId,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
        ];
    }
}
