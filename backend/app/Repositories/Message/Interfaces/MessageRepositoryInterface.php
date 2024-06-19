<?php

namespace App\Repositories\Message\Interfaces;

use Illuminate\Support\Collection;
use App\DTO\Message\CreateMesssageDTO;

interface MessageRepositoryInterface
{
    /**
     * @param int $chatId
     * 
     * @return Collection
     */
    public function getByChatId(int $chatId): Collection;

    /**
     * @param int $chatId
     * @param string $newMessageUuid
     * @param CreateMesssageDTO $createMesssageDTO
     * 
     * @return array
     */
    public function create(int $chatId, string $newMessageUuid, CreateMesssageDTO $createMesssageDTO): array;

    /**
     * @param int $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function read(int $chatId, string $messageUuid): void;

    /**
     * @param int $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function delete(int $chatId, string $messageUuid): void;
}
