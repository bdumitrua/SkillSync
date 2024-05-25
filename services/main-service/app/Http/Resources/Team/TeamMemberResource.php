<?php

namespace App\Http\Resources\Team;

use App\Http\Resources\User\UserDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
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
            'teamId' => $this->team_id,
            'userId' => $this->user_id,
            'userData' => $userData,
            'isModerator' => $this->is_moderator,
            'about' => $this->about,
        ];
    }
}
