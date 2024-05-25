<?php

namespace App\Repositories\User;

use App\DTO\User\UpdateUserDTO;
use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\UpdateFromDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class UserRepository implements UserRepositoryInterface
{
    use UpdateFromDTO;

    protected function queryById($userId): Builder
    {
        return User::query()->where('id', '=', $userId);
    }

    public function getById(int $userId): ?User
    {
        return $this->queryById($userId)->first();
    }

    public function getByIds(array $usersIds): Collection
    {
        return new Collection(array_map(function ($userId) {
            if (!empty($user = $this->getById($userId))) {
                return $user;
            }
        }, $usersIds));
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', '=', $email)->first();
    }

    public function update(int $userId, UpdateUserDTO $dto): bool
    {
        $user = $this->queryById($userId)->first();

        return $this->updateFromDto($user, $dto);
    }
}
