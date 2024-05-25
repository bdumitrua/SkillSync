<?php

namespace App\DTO\Team;

class UpdateTeamVacancyDTO
{
    public string $title;
    public string $description;

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
