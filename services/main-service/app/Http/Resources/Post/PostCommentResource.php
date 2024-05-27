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
            'userId' => $this->user_id,
            'userData' => $userData,
            'text' => $this->text,
            'mediaUrl' => $this->media_url,
            'likesCount' => $this->likes_count ?? 0,
            'created_at' => $this->created_at,
        ];
    }
}
