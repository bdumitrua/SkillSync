<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\TagResource;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\User\UserDataResource;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
        ];
    }
}
