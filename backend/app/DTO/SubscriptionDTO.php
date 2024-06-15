<?php

namespace App\DTO;

class SubscriptionDTO
{
    public int $subscriberId = 0;
    public string $entityType = '';
    public int $entityId = 0;

    public function toArray(): array
    {
        return [
            'subscriber_id' => $this->subscriberId,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
        ];
    }

    public function setSubscriberId(int $subscriberId): self
    {
        $this->subscriberId = $subscriberId;
        return $this;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;
        return $this;
    }

    public function setEntityId(string $entityId): self
    {
        $this->entityId = $entityId;
        return $this;
    }
}
