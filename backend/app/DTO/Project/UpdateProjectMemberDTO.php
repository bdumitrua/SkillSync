<?php

namespace App\DTO\Project;

class UpdateProjectMemberDTO
{
    public string $additional;

    public function toArray(): array
    {
        return [
            'additional' => $this->additional,
        ];
    }
}
