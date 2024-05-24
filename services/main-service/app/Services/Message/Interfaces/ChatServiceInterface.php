<?php

namespace App\Services\Message\Interfaces;

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
     * @param Request $request
     * 
     * @return void
     */
    public function create(Request $request): void;
}
