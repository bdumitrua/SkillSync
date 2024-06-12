<?php

namespace App\Services\Message\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;

interface ChatMemberServiceInterface
{
    /**
     * @param int $chatId
     * 
     * @return JsonResource
     */
    public function show(int $chatId): JsonResource;

    /**
     * @param int $chatId
     * @param int $userId
     * 
     * @return void
     */
    public function add(int $chatId, int $userId): void;

    /**
     * @param int $chatId
     * 
     * @return void
     */
    public function remove(int $chatId): void;
}
