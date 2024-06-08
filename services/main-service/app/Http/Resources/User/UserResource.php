<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Http\Resources\ActionsResource;
use App\Helpers\TimeHelper;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $age = TimeHelper::calculateAge($this->birthdate);

        $actions = $this->prepareActions();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tags' => $this->tags,
            'posts' => $this->posts,
            'teams' => $this->teams,
            'subscribersCount' => $this->subscribersCount ?? 0,
            'subscriptionsCount' => $this->subscriptionsCount ?? 0,
            'canSubscribe' => $this->canSubscribe,
            'actions' => $actions,
        ];
    }

    private function prepareActions(): array
    {
        $actions = [
            [
                "GetUserSubscribers",
                "users.subscribers",
                ["user" => $this->id]
            ],
            [
                "GetUserSubscriptions",
                "subscriptions.show",
                ["user" => $this->id]
            ],
        ];

        return (array) ActionsResource::collection($actions);
    }
}
