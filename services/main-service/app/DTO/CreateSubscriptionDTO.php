<?php

namespace App\DTO\User;

class CreateSubscriptionDTO
{
    public int $userId;
    public string $entityType;
    public int $entityId;

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
        ];
    }
}
