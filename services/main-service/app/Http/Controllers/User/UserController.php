<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->handleServiceCall(function () {
            return $this->userService->index();
        });
    }

    public function show(User $user)
    {
        return $this->handleServiceCall(function () use ($user) {
            return $this->userService->show($user);
        });
    }

    public function update(UpdateUserRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->userService->update($request);
        });
    }
}
