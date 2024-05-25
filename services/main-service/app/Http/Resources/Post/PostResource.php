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
            $entityData = (new UserDataResource($entityData))->resolve();
        } elseif ($this->entity_type === Team::class) {
            $entityData = (new TeamDataResource($entityData))->resolve();
        }

        $tags = (TagResource::collection($this->tagsData))->resolve();

        return [
            'id' => $this->id,
            'text' => $this->text,
            'media_url' => $this->media_url,
            'created_at' => $this->created_at,
            'entity_id' => $this->entity_id,
            'entity_type' => $this->entity_type,
            'entityData' => $entityData,
            'tags' => $tags,
            'likes_count' => $this->likes_count ?? 0,
        ];
    }
}
