<?php

namespace App\Services\Message\Interfaces;

interface MessageServiceInterface
{
    /**
     * @param int $chatId
     * 
     * @return void
     */
    public function send(int $chatId): void;

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
