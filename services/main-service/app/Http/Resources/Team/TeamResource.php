<?php

namespace App\Http\Resources\Team;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
            'chatId' => $this->chat_id,
            // TODO CHAT DATA
            'chatData' => $this->chatData,
            'adminId' => $this->admin_id,
            // TODO ADMIN DATA
            'adminData' => $this->adminData,
        ];
    }
}
