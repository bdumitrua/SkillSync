<?php

namespace App\DTO\Team;

class CreateTeamDTO
{
    public string $name;
    public int $adminId = 0;

    public ?string $avatar = null;
    public ?string $description = null;
    public ?string $email = null;
    public ?string $site = null;

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

    public function setAdminId(int $adminId): self
    {
        $this->adminId = $adminId;
        return $this;
    }
}
