<?php

namespace App\Repositories\User\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\IdentifiableRepositoryInterface;
use App\Models\User;
use App\DTO\User\UpdateUserDTO;

interface UserRepositoryInterface extends IdentifiableRepositoryInterface
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
     * @param string $query
     * 
     * @return Collection
     */
    function search(string $query): Collection;

    /**
     * @param int $userId
     * @param UpdateUserDTO $dto
     * 
     * @return bool
     */
    function update(int $userId, UpdateUserDTO $dto): bool;
}
