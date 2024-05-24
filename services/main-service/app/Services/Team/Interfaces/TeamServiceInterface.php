<?php

namespace App\Services\Team\Interfaces;

interface TeamServiceInterface
{
    /**
     * @param int $userId
     * 
     * @return array|null
     */
    public function getTeamsByUserId(int $userId): ?array;
}
