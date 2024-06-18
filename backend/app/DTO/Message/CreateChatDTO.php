<?php

namespace App\DTO\Message;

use App\Enums\ChatType;

class CreateChatDTO
{
    public string $type;

    public ?int $chatId = null;

    public ?int $firstUserId = null;
    public ?int $secondUserId = null;

    public ?string $adminType = null;
    public ?int $adminId = null;
    public ?string $name = null;
    public ?string $avatarUrl = null;

    public function toArray(bool $creatingBasicChat = false): array
    {
        if ($creatingBasicChat) {
            return [
                'type' => $this->type
            ];
        }

        if ($this->isDialog()) {
            return [
                'chat_id' => $this->chatId,
                'first_user_id' => $this->firstUserId,
                'second_user_id' => $this->secondUserId,
            ];
        } else {
            return [
                'chat_id' => $this->chatId,
                'admin_type' => $this->adminType,
                'admin_id' => $this->adminId,
                'name' => $this->name,
                'avatar_url' => $this->avatarUrl,
            ];
        }
    }

    public function isDialog(): bool
    {
        return $this->type === ChatType::Dialog->value;
    }

    public function isGroupChat(): bool
    {
        return $this->type === ChatType::Group->value;
    }

    public function isTeamChat(): bool
    {
        return $this->isGroupChat() && $this->adminType === config('entities.team');
    }

    public function isUserGroupChat(): bool
    {
        return $this->isGroupChat() && $this->adminType === config('entities.user');
    }

    public function setChatId(int $chatId): void
    {
        $this->chatId = $chatId;
    }
}
