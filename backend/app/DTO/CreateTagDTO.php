<?php

namespace App\DTO;

class CreateTagDTO
{
    public string $title;
    public string $entityType;
    public int $entityId;

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'entity_type' => $this->entityType,
            'entity_id' => $this->entityId,
        ];
    }
}
