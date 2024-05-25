<?php

namespace App\DTO\Team;

class CreateTeamDTO
{
    public string $name;
    public int $adminId;

    public ?string $avatar;
    public ?string $description;
    public ?string $email;
    public ?string $site;

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'admin_id' => $this->adminId,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
        ];
    }
}
