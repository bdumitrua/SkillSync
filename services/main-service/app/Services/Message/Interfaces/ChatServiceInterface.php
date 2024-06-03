<?php

namespace App\Services\Message\Interfaces;

use App\DTO\Message\CreateChatDTO;
use App\DTO\Message\UpdateChatDTO;
use App\Http\Requests\Message\CreateChatRequest;
use App\Http\Requests\Message\UpdateChatRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

interface ChatServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @param int $chatId
     * 
     * @return JsonResource
     */
    public function show(int $chatId): JsonResource;

    /**
     * @param int $teamId
     * @param CreateChatDTO $createChatDTO
     * 
     * @return void
     */
    public function create(int $teamId, CreateChatDTO $createChatDTO): void;

    /**
     * @param int $chatId
     * @param UpdateChatDTO $updateChatDTO
     * 
     * @return void
     */
    public function update(int $chatId, UpdateChatDTO $updateChatDTO): void;
}
