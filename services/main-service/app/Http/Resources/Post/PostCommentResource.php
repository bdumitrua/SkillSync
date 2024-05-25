<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\User\UserDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userData = (new UserDataResource($this->userData))->resolve();

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'userData' => $userData,
            'text' => $this->text,
            'media_url' => $this->media_url,
            'created_at' => $this->created_at,
            'likes_count' => $this->likes_count ?? 0,
        ];
    }
}
