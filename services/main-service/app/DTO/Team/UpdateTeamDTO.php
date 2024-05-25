<?php

namespace App\DTO\Team;

class UpdateTeamDTO
{
    public ?string $name;

    public ?string $avatar;
    public ?string $description;
    public ?string $email;
    public ?string $site;
    public ?int $chatId;

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
