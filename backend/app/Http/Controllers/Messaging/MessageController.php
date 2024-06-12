<?php

namespace App\Http\Controllers\Messaging;

use App\DTO\Message\CreateMesssageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Message\CreateMesssageRequest;
use App\Services\Message\Interfaces\MessageServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    private $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function send(int $chatId, CreateMesssageRequest $request)
    {
        /** @var CreateMesssageDTO */
        $createMessageDTO = $request->createDTO();
        $createMessageDTO->setSenderId(Auth::id())->setChatId($chatId);

        return $this->handleServiceCall(function () use ($chatId, $createMessageDTO) {
            return $this->messageService->send($chatId, $createMessageDTO);
        });
    }

    public function read(int $chatId, string $messageUuid)
    {
        return $this->handleServiceCall(function () use ($chatId, $messageUuid) {
            return $this->messageService->read($chatId, $messageUuid);
        });
    }

    public function delete(int $chatId, string $messageUuid)
    {
        return $this->handleServiceCall(function () use ($chatId, $messageUuid) {
            return $this->messageService->delete($chatId, $messageUuid);
        });
    }
}
