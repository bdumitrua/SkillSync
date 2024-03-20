<?php

namespace App\Services;

use App\AuthRegistration;
use App\AuthReset;
use App\Http\Requests\AuthConfirmCodeRequest;
use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;

class AuthService
{
    public function registrationStart(CreateUserRequest $request)
    {
        // 
    }

    public function registrationConfirm(AuthRegistration $authRegistration, AuthConfirmCodeRequest $request)
    {
        // 
    }

    public function resetCheck(CheckEmailRequest $request)
    {
        // 
    }

    public function resetConfirm(AuthReset $authReset, AuthConfirmCodeRequest $request)
    {
        // 
    }

    public function resetEnd(AuthReset $authReset, PasswordRequest $request)
    {
        // 
    }

    public function login(LoginRequest $request)
    {
        // 
    }

    public function logout(Request $request)
    {
        // 
    }

    public function refresh()
    {
        // 
    }
}
