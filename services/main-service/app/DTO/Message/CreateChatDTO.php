<?php

namespace App\DTO\Message;

class CreateChatDTO
{
    public string $name;
    public int $chatId;
    public int $adminId;
    public int $teamId;

    public ?string $avatarUrl;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'chatId' => $this->chatId,
            'adminId' => $this->adminId,
            'teamId' => $this->teamId,
            'avatarUrl' => $this->avatarUrl,
        ];
    }

    public function setAdminId(int $adminId): self
    {
        $this->adminId = $adminId;
        return $this;
    }

    public function setChatId(int $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }
}
