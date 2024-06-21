<?php

namespace App\Firebase;

use Illuminate\Database\Eloquent\Collection;
use App\Models\NoSQL\Message;
use App\DTO\Message\CreateMesssageDTO;

interface FirebaseServiceInterface
{
    /**
     * @param int $chatId
     * @param string $newMessageUuid
     * @param CreateMesssageDTO $messageData
     * 
     * @return Message
     */
    public function sendMessage(int $chatId, string $newMessageUuid, CreateMesssageDTO $messageData): Message;

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
     * @return Collection
     */
    public function getChatMessages(int $chatId): Collection;

    /**
     * @param array $chatIds
     * 
     * @return Collection
     */
    public function getChatsMessages(array $chatIds): Collection;
}
