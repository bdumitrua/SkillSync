<?php

namespace App\Services\Message\Interfaces;

use App\Http\Requests\Message\CreateMesssageRequest;

interface MessageServiceInterface
{
    /**
     * @param int $chatId
     * @param CreateMesssageRequest $request
     * 
     * @return void
     */
    public function send(int $chatId, CreateMesssageRequest $request): void;

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
