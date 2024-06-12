<?php

namespace App\Repositories\Message\Interfaces;

interface ChatMemberRepositoryInterface
{
    /**
     * @param int $chatId
     * 
     * @return array|null
     */
    public function getByChatId(int $chatId): ?array;
}
