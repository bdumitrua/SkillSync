<?php

namespace App\Http\Controllers\Messaging;

use App\Http\Controllers\Controller;
use App\Http\Requests\Message\CreateMesssageRequest;
use App\Services\Message\Interfaces\MessageServiceInterface;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private $messageService;

    public function __construct(MessageServiceInterface $messageService)
    {
        $this->messageService = $messageService;
    }

    public function send(int $chatId, CreateMesssageRequest $request)
    {
        return $this->handleServiceCall(function () use ($chatId, $request) {
            return $this->messageService->send($chatId, $request);
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
