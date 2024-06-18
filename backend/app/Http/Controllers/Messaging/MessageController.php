<?php

namespace App\Http\Controllers\Messaging;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\Message\Interfaces\MessageServiceInterface;
use App\Models\Chat;
use App\Http\Requests\Message\CreateMesssageRequest;
use App\Http\Controllers\Controller;
use App\DTO\Message\CreateMesssageDTO;

class MessageController extends Controller
{
    private $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function send(Chat $chat, CreateMesssageRequest $request)
    {
        /** @var CreateMesssageDTO */
        $createMessageDTO = $request->createDTO();
        $createMessageDTO->setSenderId(Auth::id());

        return $this->handleServiceCall(function () use ($chat, $createMessageDTO) {
            return $this->messageService->send($chat, $createMessageDTO);
        });
    }

    public function read(Chat $chat, string $messageUuid)
    {
        return $this->handleServiceCall(function () use ($chat, $messageUuid) {
            return $this->messageService->read($chat, $messageUuid);
        });
    }

    public function delete(Chat $chat, string $messageUuid)
    {
        return $this->handleServiceCall(function () use ($chat, $messageUuid) {
            return $this->messageService->delete($chat, $messageUuid);
        });
    }
}
