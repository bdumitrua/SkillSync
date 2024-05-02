<?php

namespace App\DTO;

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
            'last_name' => $this->firstName,
            'email' => $this->firstName,
            'birthdate' => $this->firstName,
            'phone' => $this->firstName,
            'nick_name' => $this->firstName,
            'about' => $this->firstName,
            'address' => $this->firstName,
            'avatar' => $this->firstName,
            'gender' => $this->firstName,
        ];
    }
}
