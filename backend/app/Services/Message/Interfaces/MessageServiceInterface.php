<?php

namespace App\Services\Message\Interfaces;

use App\DTO\Message\CreateMesssageDTO;
use App\Http\Requests\Message\CreateMesssageRequest;

interface MessageServiceInterface
{
    /**
     * @param int $chatId
     * @param CreateMesssageDTO $createMesssageDTO
     * 
     * @return void
     */
    public function send(int $chatId, CreateMesssageDTO $createMesssageDTO): void;

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
