<?php

namespace App\Services\Message\Interfaces;

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
     * @param CreateChatRequest $request
     * 
     * @return void
     */
    public function create(int $teamId, CreateChatRequest $request): void;

    /**
     * @param int $chatId
     * @param UpdateChatRequest $request
     * 
     * @return void
     */
    public function update(int $chatId, UpdateChatRequest $request): void;
}
