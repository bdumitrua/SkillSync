<?php

namespace App\Services;

use App\DTO\Message\CreateMesssageDTO;
use App\Enums\MessageStatus;
use App\Exceptions\AccessDeniedException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnprocessableContentException;
use Kreait\Firebase\Database;
use Ramsey\Uuid\Uuid;

class FirebaseService
{
    protected Database $database;
    protected string $bucket;

    public function __construct()
    {
        $this->database = app('firebase.database');
        $this->bucket = config('firebase.projects.app.storage.bucket');
    }

    public function wipeMyData(): bool
    {
        $bucketReference = $this->database->getReference($this->bucket);
        if (!$bucketReference->getSnapshot()->exists()) {
            return false;
        }

        $bucketReference->remove();
        return true;
    }

    public function createChat(string $chatId, array $chatData): void
    {
        $chatRef = $this->database->getReference($this->getChatDataPath($chatId));

        $snapshot = $chatRef->getSnapshot();
        if ($snapshot->exists()) {
            throw new UnprocessableContentException("Chat with ID {$chatId} already exists.");
        }

        $chatRef->set($chatData);
        $this->setLastChatId($chatId);
        $this->addChatMember($chatId, $chatData['adminId']);
    }

    public function setLastChatId(string $chatId): void
    {
        $lastIdRef = $this->database->getReference($this->getConfigLastChatIdPath());
        $lastIdRef->set($chatId);
    }

    public function getLastChatId(): int
    {
        $lastIdRef = $this->database->getReference($this->getConfigLastChatIdPath());
        $snapshot = $lastIdRef->getSnapshot();

        if (!$snapshot->exists()) {
            $lastIdRef->set(0);
            return 0;
        }

        return $snapshot->getValue();
    }

    public function addChatMember(string $chatId, int $userId): void
    {
        $updates = [
            $this->getChatMemberPath($chatId, $userId) => true,
            $this->getUserChatPath($chatId, $userId) => true,
        ];

        $this->database->getReference()->update($updates);
    }

    public function removeChatMember(string $chatId, int $userId): void
    {
        $chatMemberRef = $this->database->getReference($this->getChatMemberPath($chatId, $userId));
        $userChatRef = $this->database->getReference($this->getUserChatPath($chatId, $userId));

        if ($chatMemberRef->getSnapshot()->exists()) {
            $chatMemberRef->remove();
        }

        if ($userChatRef->getSnapshot()->exists()) {
            $userChatRef->remove();
        }
    }

    public function sendMessage(string $chatId, CreateMesssageDTO $messageData): void
    {
        $newMessageUuid = Uuid::uuid4()->toString();
        $newMessageRef = $this->database->getReference($this->getMessagePath($chatId, $newMessageUuid));

        $newMessageRef->set($messageData->toArray());
    }

    public function readMessage(string $chatId, string $messageUuid): void
    {
        $messageRef = $this->database->getReference($this->getMessagePath($chatId, $messageUuid));
        $snapshot = $messageRef->getSnapshot();

        if (!$snapshot->exists()) {
            throw new NotFoundException("Message");
        }

        $messageData = $snapshot->getValue();
        if ($messageData['status'] === MessageStatus::Sended) {
            $messageRef->update(['status' => MessageStatus::Readed]);
        }
    }

    public function deleteMessage(string $chatId, int $userId, string $messageUuid): void
    {
        $messageRef = $this->database->getReference($this->getMessagePath($chatId, $messageUuid));
        $snapshot = $messageRef->getSnapshot();

        if (!$snapshot->exists()) {
            throw new NotFoundException("Message");
        }

        $messageData = $snapshot->getValue();
        if ($messageData['senderId'] !== $userId) {
            throw new AccessDeniedException("You can't delete other member's messages.");
        }

        $messageRef->remove();
    }

    public function getChatMessages(string $chatId): array
    {
        $chatMessagesRef = $this->database->getReference($this->getChatMessagesPath($chatId));
        $snapshot = $chatMessagesRef->getSnapshot();

        return $snapshot->getValue() ?? [];
    }

    public function userIsChatMember(int $userId, string $chatId): bool
    {
        $chatMemberRef = $this->database->getReference($this->getChatMemberPath($chatId, $userId));

        return $chatMemberRef->getSnapshot()->exists();
    }

    public function getChatMembers(string $chatId): array
    {
        $chatMembersRef = $this->database->getReference($this->getChatMembersPath($chatId));
        $snapshot = $chatMembersRef->getSnapshot();

        if (!$snapshot->exists()) {
            throw new NotFoundException("Chat members");
        }

        return $snapshot->getValue();
    }

    public function getChatData(string $chatId): ?array
    {
        $chatRef = $this->database->getReference($this->getChatDataPath($chatId));
        $snapshot = $chatRef->getSnapshot();

        return $snapshot->getValue();
    }

    public function getMultipleChatsData(array $chatIds): array
    {
        $chatDataPromises = array_map(function ($chatId) {
            $chatRef = $this->database->getReference($this->getChatDataPath($chatId));
            $snapshot = $chatRef->getSnapshot();
            return $snapshot->exists() ? ['chatId' => $chatId] + $snapshot->getValue() : null;
        }, $chatIds);

        return array_filter($chatDataPromises);
    }

    public function getUserChats(int $userId): array
    {
        $userChatsRef = $this->database->getReference($this->getUserChatsPath($userId));
        $snapshot = $userChatsRef->getSnapshot();

        return $snapshot->getValue() ?? [];
    }

    protected function getChatDataPath(int $chatId): string
    {
        return $this->bucket . "/chats/{$chatId}";
    }

    protected function getChatMembersPath(int $chatId): string
    {
        return $this->bucket . "/chatMembers/{$chatId}";
    }

    protected function getChatMemberPath(int $chatId, int $userId): string
    {
        return $this->bucket . "/chatMembers/{$chatId}/{$userId}";
    }

    protected function getUserChatsPath(int $userId): string
    {
        return $this->bucket . "/userChats/{$userId}";
    }

    protected function getUserChatPath(int $chatId, int $userId): string
    {
        return $this->bucket . "/userChats/{$userId}/{$chatId}";
    }

    protected function getChatMessagesPath(int $chatId): string
    {
        return $this->bucket . "/messages/{$chatId}";
    }

    protected function getMessagePath(int $chatId, string $messageUuid): string
    {
        return $this->bucket . "/messages/{$chatId}/{$messageUuid}";
    }

    protected function getConfigLastChatIdPath(): string
    {
        return $this->bucket . '/config/lastChatId';
    }
}
