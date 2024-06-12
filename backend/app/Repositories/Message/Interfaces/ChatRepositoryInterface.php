<?php

namespace App\Repositories\Message\Interfaces;

interface ChatRepositoryInterface
{
    /**
     * @param int $chatId
     * 
     * @return array|null
     */
    public function getById(int $chatId): ?array;

    /**
     * @param int $userId
     * 
     * @return array
     */
    public function getByUserId(int $userId): array;
}
