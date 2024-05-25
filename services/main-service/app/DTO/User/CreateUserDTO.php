<?php

namespace App\DTO\User;

class CreateUserDTO
{
    public string $firstName;
    public string $lastName;
    public string $password;
    public string $email;
    public string $birthdate;

    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'password' => $this->password,
            'email' => $this->email,
            'birthdate' => $this->birthdate,
        ];
    }
}
