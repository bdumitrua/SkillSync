<?php

namespace App\Services\Message\Interfaces;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Chat;

interface ChatMemberServiceInterface
{
    /**
     * @param Chat $chat
     * 
     * @return JsonResource
     */
    public function show(Chat $chat): JsonResource;

    /**
     * @param Chat $chat
     * @param User $user
     * 
     * @return void
     */
    public function add(Chat $chat, User $user): void;

    /**
     * @param Chat $chat
     * @param User $user
     * 
     * @return void
     */
    public function delete(Chat $chat, User $user): void;
}
