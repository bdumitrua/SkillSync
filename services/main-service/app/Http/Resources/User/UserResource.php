<?php

namespace App\Http\Resources\User;

use App\Helpers\TimeHelper;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\Team\TeamDataResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $age = TimeHelper::calculateAge($this->birthdate);
        $tags = (TagResource::collection($this->tags))->resolve();
        $teams = (TeamDataResource::collection($this->teams ?? []))->resolve();
        $posts = (PostResource::collection($this->posts ?? []))->resolve();

        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'nickName' => $this->nick_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'age' => $age,
            'tags' => $tags,
            'posts' => $posts,
            'teams' => $teams,
            'subscribersCount' => $this->subscribersCount ?? 0,
            'subscriptionsCount' => $this->subscriptionsCount ?? 0,
            'canSubscribe' => $this->canSubscribe,
        ];
    }
}
