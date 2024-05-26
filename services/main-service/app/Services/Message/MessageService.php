<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Auth;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Http\Requests\Message\CreateMesssageRequest;

class MessageService implements MessageServiceInterface
{
    protected $messageRepository;
    protected ?int $authorizedUserId;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function send(int $chatId, CreateMesssageRequest $request): void
    {
        // TODO GATE: Check if authorized user is chat member
        // 
    }

    public function read(int $chatId, string $messageUuid): void
    {
        // 
        // TODO GATE: Check if authorized user is chat member
    }

    public function delete(int $chatId, string $messageUuid): void
    {
        // 
        // TODO GATE: Check if authorized user is author
    }
}
