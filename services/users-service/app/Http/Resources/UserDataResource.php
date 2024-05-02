<?php

namespace App\Http\Resources;

use App\Helpers\TimeHelper;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
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
            'age' => $age
        ];
    }
}
