<?php

namespace App\DTO;

use App\Enums\NotificationStatus;

class CreateNotificationDTO
{
    public int $receiverId;
    public string $type;
    public string $status;

    public ?string $fromType;
    public ?int $fromId;
    public ?string $toType;
    public ?int $toId;

    public function __construct(
        int $receiverId,
        string $type,
        ?int $fromId = null,
        ?string $fromType = null,
        ?int $toId = null,
        ?string $toType = null,
    ) {
        $this->receiverId = $receiverId;
        $this->type = $type;
        $this->fromId = $fromId;
        $this->fromType = $fromType;
        $this->toId = $toId;
        $this->toType = $toType;
        $this->status = NotificationStatus::Unseen;
    }

    public function toArray(): array
    {
        return [
            'receiver_id' => $this->receiverId,
            'type' => $this->type,
            'status' => $this->status,
            'from_type' => $this->fromType,
            'from_id' => $this->fromId,
            'to_type' => $this->toType,
            'to_id' => $this->toId,
        ];
    }
}
