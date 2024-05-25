<?php

namespace App\DTO\Team;

class UpdateTeamApplicationDTO
{
    public string $status;

    public function toArray(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
