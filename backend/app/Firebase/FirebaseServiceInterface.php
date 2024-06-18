<?php

namespace App\Firebase;

use App\DTO\Message\CreateMesssageDTO;

interface FirebaseServiceInterface
{
    /**
     * @param int $chatId
     * @param string $newMessageUuid
     * @param CreateMesssageDTO $messageData
     * 
     * @return void
     */
    public function sendMessage(int $chatId, string $newMessageUuid, CreateMesssageDTO $messageData): void;

    /**
     * @param string $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function readMessage(string $chatId, string $messageUuid): void;

    /**
     * @param string $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function deleteMessage(string $chatId, string $messageUuid): void;

    /**
     * @param string $chatId
     * 
     * @return array
     */
    public function getChatMessages(string $chatId): array;

    /**
     * @param array $chatIds
     * 
     * @return array
     */
    public function getChatsMessages(array $chatIds): array;
}
