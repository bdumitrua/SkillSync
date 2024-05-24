<?php

namespace App\Services\Team;

use App\Services\Team\Interfaces\TeamServiceInterface;

class TeamService implements TeamServiceInterface
{
    public function getTeamsByUserId(int $userId): ?array
    {
        return [];
    }
}
