<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\Message\ChatResource;
use App\Http\Resources\User\UserDataResource;
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
        $chatData = $this->chatData ? (new ChatResource($this->chatData))->resolve() : [];
        $adminData = $this->adminData ? (new UserDataResource($this->adminData))->resolve() : [];

        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'description' => $this->description,
            'email' => $this->email,
            'site' => $this->site,
            'chatId' => $this->chat_id,
            'chatData' => $chatData,
            'adminId' => $this->admin_id,
            'adminData' => $adminData,
            'posts' => $this->posts,
            'tags' => $this->tags,
            'links' => $this->links,
        ];
    }
}
