<?php

namespace App\Http\Resources\User;

use App\Helpers\TimeHelper;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $age = TimeHelper::calculateAge($this->birthdate);

        return [
            'id' => $this->id,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'nickName' => $this->nick_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'gender' => $this->gender,
            'birthdate' => $this->birthdate,
            'age' => $age,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
