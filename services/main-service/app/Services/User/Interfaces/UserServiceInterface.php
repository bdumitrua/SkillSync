<?php

namespace App\Services\User\Interfaces;

use App\Models\User;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

interface UserServiceInterface
{
    /**
     * @return JsonResource
     */
    public function index(): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function show(User $user): JsonResource;

    /**
     * @param UpdateUserRequest $request
     * 
     * @return void
     */
    public function update(UpdateUserRequest $request): void;
}
