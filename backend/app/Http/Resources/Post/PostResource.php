<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\ActionsResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $authorData = [];
        if ($this->author_type === config('entities.user')) {
            $authorData = (new UserDataResource($this->authorData))->resolve();
        } elseif ($this->author_type === config('entities.team')) {
            $authorData = (new TeamDataResource($this->authorData))->resolve();
        }

        $tags = $this->tagsData ? (TagResource::collection($this->tagsData))->resolve() : [];
        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'text' => $this->text,
            'mediaUrl' => $this->media_url,
            'createdAt' => $this->created_at,
            'authorId' => $this->author_id,
            'authorType' => $this->author_type,
            'authorData' => $authorData,
            'tags' => $tags,
            'likesCount' => $this->likes_count ?? 0,
            'isLiked' => $this->isLiked,
            'canUpdate' => $this->canUpdate,
            'canDelete' => $this->canDelete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'actions' => $actions
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->canDelete) {
            $actions[] = [
                'DeletePost',
                'posts.delete',
                ['post' => $this->id]
            ];
        }

        return (array) ActionsResource::collection($actions);
    }
}
