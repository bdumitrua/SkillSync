<?php

namespace App\Services\Message\Interfaces;

use App\Models\Chat;
use App\DTO\Message\CreateMesssageDTO;

interface MessageServiceInterface
{
    /**
     * @param Chat $chat
     * @param CreateMesssageDTO $createMesssageDTO
     * 
     * @return void
     */
    public function send(Chat $chat, CreateMesssageDTO $createMesssageDTO): void;

    /**
     * @param Chat $chat
     * @param string $messageUuid
     * 
     * @return void
     */
    public function read(Chat $chat, string $messageUuid): void;

    /**
     * @param Chat $chat
     * @param string $messageUuid
     * 
     * @return void
     */
    public function delete(Chat $chat, string $messageUuid): void;
}
