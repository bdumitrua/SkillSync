<?php

namespace App\Services\Message;

use Illuminate\Support\Facades\Auth;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Repositories\Message\Interfaces\MessageRepositoryInterface;
use App\Models\Chat;
use App\Http\Requests\Message\CreateMesssageRequest;
use App\DTO\Message\CreateMesssageDTO;

class MessageService implements MessageServiceInterface
{
    protected $messageRepository;
    protected ?int $authorizedUserId;

    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function send(Chat $chat, CreateMesssageDTO $createMesssageDTO): void
    {
        // 
    }

    public function read(Chat $chat, string $messageUuid): void
    {
        // 
    }

    public function delete(Chat $chat, string $messageUuid): void
    {
        // 
    }
}
