<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthConfirmCodeRequest;
use App\Http\Requests\CheckEmailRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\AuthReset;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(CreateUserRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->register($request);
        });
    }

    public function resetCheck(CheckEmailRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->resetCheck($request);
        });
    }

    public function resetConfirm(AuthReset $authReset, AuthConfirmCodeRequest $request)
    {
        return $this->handleServiceCall(function () use ($authReset, $request) {
            return $this->authService->resetConfirm($authReset, $request);
        });
    }

    public function resetEnd(AuthReset $authReset, PasswordRequest $request)
    {
        return $this->handleServiceCall(function () use ($authReset, $request) {
            return $this->authService->resetEnd($authReset, $request);
        });
    }

    public function login(LoginRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->login($request);
        });
    }

    public function logout(Request $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->authService->logout($request);
        });
    }

    public function refresh()
    {
        return $this->handleServiceCall(function () {
            return $this->authService->refresh();
        });
    }
}
