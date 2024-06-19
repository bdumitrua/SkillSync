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
     * @return array
     */
    public function sendMessage(int $chatId, string $newMessageUuid, CreateMesssageDTO $messageData): array;

    /**
     * @param int $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function readMessage(int $chatId, string $messageUuid): void;

    /**
     * @param int $chatId
     * @param string $messageUuid
     * 
     * @return void
     */
    public function deleteMessage(int $chatId, string $messageUuid): void;

    /**
     * @param int $chatId
     * 
     * @return array
     */
    public function getChatMessages(int $chatId): array;

    /**
     * @param array $chatIds
     * 
     * @return array
     */
    public function getChatsMessages(array $chatIds): array;
}
