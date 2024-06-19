<?php

namespace App\Services\Message\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Http\Requests\Message\UpdateChatRequest;
use App\Http\Requests\Message\CreateChatRequest;
use App\DTO\Message\UpdateChatDTO;
use App\DTO\Message\CreateChatDTO;

interface ChatServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @param string $query
     * 
     * @return JsonResource
     */
    public function search(string $query): JsonResource;

    /**
     * @param Chat $chat
     * 
     * @return JsonResource
     */
    public function show(Chat $chat): JsonResource;

    /**
     * @param CreateChatDTO $createChatDTO
     * @param array $memberIds
     * 
     * @return void
     */
    public function create(CreateChatDTO $createChatDTO, array $memberIds): void;

    /**
     * @param Chat $chat
     * @param UpdateChatDTO $updateChatDTO
     * 
     * @return void
     */
    public function update(Chat $chat, UpdateChatDTO $updateChatDTO): void;
}
