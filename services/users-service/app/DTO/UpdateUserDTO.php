<?php

namespace App\DTO;

class UpdateUserDTO
{
    // 'firstName' => 'required|string|max:255',
    //         'lastName' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255',
    //         'birthdate' => 'required|date|date_format:Y-m-d',

    //         'phone' => 'string|min:5',
    //         'nickName' => 'string|min:3|max:30',
    //         'about' => 'string|max:255',
    //         'address' => 'string|max:255',
    //         'avatar' => 'string',
    //         'gender' => 'string|in:man,woman'

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
