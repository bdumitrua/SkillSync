<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostCommentResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($likeable = !empty($this->likeable)) {
            if ($this->likeable_type === config('entities.post')) {
                $likeable = (new PostResource($this->likeable));
            } elseif ($this->likeable_type === config('entities.postComment')) {
                $likeable = (new PostCommentResource($this->likeable));
            } elseif ($this->likeable_type === config('entities.project')) {
                $likeable = (new ProjectResource($this->likeable));
            }
        }

        $userData = !empty($this->userData)
            ? (new UserDataResource($this->userData))->resolve()
            : [];

        return [
            "id" => $this->id,
            "userId" => $this->user_id,
            "likeableType" => $this->likeable_type,
            "likeableId" => $this->likeable_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "likeable" => $likeable,
            "userData" => $userData,
        ];
    }
}
