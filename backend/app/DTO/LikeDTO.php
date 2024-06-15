<?php

namespace App\DTO;

class LikeDTO
{
    public int $userId = 0;
    public string $likeableType = '';
    public int $likeableId = 0;

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'likeable_type' => $this->likeableType,
            'likeable_id' => $this->likeableId,
        ];
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function setLikeableType(string $likeableType): self
    {
        $this->likeableType = $likeableType;
        return $this;
    }

    public function setLikeableId(string $likeableId): self
    {
        $this->likeableId = $likeableId;
        return $this;
    }
}
