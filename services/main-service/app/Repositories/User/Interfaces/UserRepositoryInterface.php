<?php

namespace App\Repositories\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\DTO\User\UpdateUserDTO;
use App\Models\User;

interface UserRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return User|null
     */
    function getById(int $userId): ?User;

    /**
     * @param array $usersIds
     * 
     * @return Collection
     */
    function getByIds(array $usersIds): Collection;

    /**
     * @param string $email
     * 
     * @return User|null
     */
    function getByEmail(string $email): ?User;

    /**
     * @param int $userId
     * @param UpdateUserDTO $dto
     * 
     * @return bool
     */
    function update(int $userId, UpdateUserDTO $dto): bool;
}
