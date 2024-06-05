<?php

namespace App\Services\User\Interfaces;

use App\DTO\User\UpdateUserDTO;
use App\Models\User;
use App\Http\Requests\User\UpdateUserRequest;
use Illuminate\Http\Request;
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
     * @param Request $request
     * 
     * @return JsonResource
     */
    public function search(Request $request): JsonResource;

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function subscribers(User $user): JsonResource;

    /**
     * @param UpdateUserDTO $updateUserDTO
     * 
     * @return void
     */
    public function update(UpdateUserDTO $updateUserDTO): void;
}
