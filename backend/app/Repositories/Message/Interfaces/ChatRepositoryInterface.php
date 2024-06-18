<?php

namespace App\Repositories\Message\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Chat;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

interface ChatRepositoryInterface
{
    /**
     * @param int $chatId
     * 
     * @return array|null
     */
    public function getById(int $chatId): ?array;

    /**
     * @param array $chatIds
     * 
     * @return Collection
     */
    public function getDataByIds(array $chatIds): Collection;

    /**
     * @param int $userId
     * 
     * @return array
     */
    public function getByUserId(int $userId): array;

    /**
     * @param CreateChatDTO $createChatDTO
     * 
     * @return Chat
     */
    public function create(CreateChatDTO $createChatDTO): Chat;

    /**
     * @param Chat $chat
     * @param UpdateChatDTO $updateChatDTO
     * 
     * @return void
     */
    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void;
}
