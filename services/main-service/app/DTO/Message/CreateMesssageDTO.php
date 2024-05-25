<?php

namespace App\DTO\Message;

class CreateMesssageDTO
{
    public string $text;
    public int $chatId;
    public int $senderId;
    public string $status = "unread";

    public function toArray(): array
    {
        return [
            'text' => $this->text,
            'chatId' => $this->chatId,
            'senderId' => $this->senderId,
            'status' => $this->status,
        ];
    }
}
