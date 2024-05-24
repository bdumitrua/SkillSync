<?php

namespace App\Services\Message;

use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Services\Message\Interfaces\MessageServiceInterface;
use Illuminate\Support\Facades\Auth;

class MessageService implements MessageServiceInterface
{
    protected $messageRepository;
    protected ?int $authorizedUserId;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function send(int $chatId): void
    {
        // 
    }

    public function read(int $chatId, string $messageUuid): void
    {
        // 
    }

    public function delete(int $chatId, string $messageUuid): void
    {
        // 
    }
}
