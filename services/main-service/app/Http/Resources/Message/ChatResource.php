<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'avatarUrl' => $this->avatarUrl,
            'chatId' => $this->chatId,
            'adminId' => $this->adminId,
            'teamId' => $this->teamId,
        ];
    }
}
