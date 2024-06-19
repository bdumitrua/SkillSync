<?php

namespace App\Services\User\Interfaces;

use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\User\UpdateUserRequest;
use App\DTO\User\UpdateUserDTO;

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
     * @param string $query
     * 
     * @return JsonResource
     */
    public function search(string $query): JsonResource;

    /**
     * @param UpdateUserDTO $updateUserDTO
     * 
     * @return void
     */
    public function update(UpdateUserDTO $updateUserDTO): void;
}
