<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostCommentResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $typesResources = [
            config('entities.user') => UserDataResource::class,
            config('entities.team') => TeamDataResource::class,
            config('entities.post') => PostResource::class,
            config('entities.postComment') => PostCommentResource::class,
            config('entities.project') => ProjectResource::class,
        ];

        $fromResourceClass = $typesResources[$this->from_type];
        $fromData = !empty($this->fromData)
            ? (new $fromResourceClass($this->fromData))
            : [];

        $toResourceClass = $typesResources[$this->to_type];
        $toData = !empty($this->toData)
            ? (new $toResourceClass($this->toData))
            : [];

        return [
            'id' => $this->id,
            'receiverId' => $this->receiver_id,
            'type' => $this->type,
            'status' => $this->status,
            'fromId' => $this->from_id,
            'fromType' => $this->from_type,
            'fromData' => $fromData,
            'toId' => $this->to_id,
            'toType' => $this->to_type,
            'toData' => $toData,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
