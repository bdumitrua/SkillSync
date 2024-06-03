<?php

namespace App\DTO\Message;

use App\Enums\MessageStatus;

class CreateMesssageDTO
{
    public string $text;
    public int $chatId;
    public int $senderId;
    public string $status = MessageStatus::Sended;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'chatId' => $this->chatId,
            'senderId' => $this->senderId,
            'status' => $this->status,
        ];
    }

    public function setChatId(int $chatId): self
    {
        $this->chatId = $chatId;
        return $this;
    }

    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;
        return $this;
    }
}
