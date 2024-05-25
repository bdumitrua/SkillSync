<?php

namespace App\DTO\User;

class UpdateUserDTO
{
    public string $firstName;
    public string $lastName;
    public string $email;
    public string $birthdate;

    public ?string $phone = null;
    public ?string $nickName = null;
    public ?string $about = null;
    public ?string $address = null;
    public ?string $avatar = null;
    public ?string $gender = null;

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
            'phone' => $this->phone,
            'nick_name' => $this->nickName,
            'about' => $this->about,
            'address' => $this->address,
            'avatar' => $this->avatar,
            'gender' => $this->gender,
        ];
    }
}
