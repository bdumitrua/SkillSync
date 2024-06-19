<?php

namespace App\DTO\Message;

use App\Enums\MessageStatus;

class CreateMesssageDTO
{
    public string $text;
    public int $senderId = 0;
    public string $status = MessageStatus::Sended->value;

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
