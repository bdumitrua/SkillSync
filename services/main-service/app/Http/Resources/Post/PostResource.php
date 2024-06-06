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
        $entityData = [];
        if ($this->entity_type === User::class) {
            $entityData = (new UserDataResource($this->entityData))->resolve();
        } elseif ($this->entity_type === Team::class) {
            $entityData = (new TeamDataResource($this->entityData))->resolve();
        }

        $tags = $this->tagsData ? (TagResource::collection($this->tagsData))->resolve() : [];
        $actions = $this->prepareActions();

        return [
            'id' => $this->id,
            'text' => $this->text,
            'mediaUrl' => $this->media_url,
            'createdAt' => $this->created_at,
            'entityId' => $this->entity_id,
            'entityType' => $this->entity_type,
            'entityData' => $entityData,
            'tags' => $tags,
            'likesCount' => $this->likes_count ?? 0,
            'isLiked' => $this->isLiked,
            'canUpdate' => $this->canUpdate,
            'canDelete' => $this->canDelete,
            'actions' => $actions,
        ];
    }

    private function prepareActions(): array
    {
        $actions = [];

        if ($this->isLiked) {
            $actions[] = [
                'UnlikePost',
                'posts.likes.delete',
                ['post' => $this->id]
            ];
        } else {
            $actions[] = [
                'LikePost',
                'posts.likes.create',
                ['post' => $this->id]
            ];
        }

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
