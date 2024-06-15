<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\ActionsResource;

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
        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'userData' => $userData,
            'text' => $this->text,
            'mediaUrl' => $this->media_url,
            'likesCount' => $this->likes_count ?? 0,
            'created_at' => $this->created_at,
            'isLiked' => $this->isLiked,
            'actions' => $actions
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->canDelete) {
            $actions[] = [
                'DeletePostComment',
                'posts.comments.delete',
                ['postComment' => $this->id]
            ];
        }

        return (array) ActionsResource::collection($actions);
    }
}
