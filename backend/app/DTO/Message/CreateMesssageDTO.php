<?php

namespace App\DTO\Message;

use App\Enums\MessageStatus;

class CreateMesssageDTO
{
    public string $text;
    public int $senderId;
    public string $status = MessageStatus::Sended;

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'senderId' => $this->senderId,
            'status' => $this->status,
        ];
    }

    public function setSenderId(int $senderId): self
    {
        $this->senderId = $senderId;
        return $this;
    }
}
