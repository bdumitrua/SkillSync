<?php

namespace App\Http\Resources\User;

use App\Helpers\TimeHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $age = TimeHelper::calculateAge($this->birthdate);
        $interests = (UserInterestResource::collection($this->interests))->resolve();

        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'nickName' => $this->nick_name,
            'interests' => $interests,
            // TODO RESOURCE
            'teams' => $this->teams,
            // TODO RESOURCE
            'posts' => $this->posts,
            'subscribersCount' => $this->subscribersCount,
            'subscriptionsCount' => $this->subscriptionsCount,
            'phone' => $this->phone,
            'email' => $this->email,
            'about' => $this->about,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'age' => $age,
            'canSubscribe' => $this->canSubscribe,
        ];
    }
}
