<?php

namespace App\DTO;

use App\Enums\NotificationType;
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
        int $receiverId = 0,
        NotificationType $type = NotificationType::Subscription,
        ?int $fromId = null,
        ?string $fromType = null,
        ?int $toId = null,
        ?string $toType = null,
    ) {
        $this->receiverId = $receiverId;
        $this->type = $type->value;
        $this->fromId = $fromId;
        $this->fromType = $fromType;
        $this->toId = $toId;
        $this->toType = $toType;
        $this->status = NotificationStatus::Unseen->value;
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

    public static function create(): self
    {
        return new self();
    }

    public function setReceiverId(int $receiverId): self
    {
        $this->receiverId = $receiverId;
        return $this;
    }

    public function setType(NotificationType $type): self
    {
        $this->type = $type->value;
        return $this;
    }

    public function setFromWho(int $entityId, string $entityType): self
    {
        $this->fromId = $entityId;
        $this->fromType = $entityType;
        return $this;
    }

    public function setToWhat(int $entityId, string $entityType): self
    {
        $this->toId = $entityId;
        $this->toType = $entityType;
        return $this;
    }
}
