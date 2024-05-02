<?php

namespace App\Repository;

use App\DTO\UpdateUserDTO;
use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Traits\UpdateFromDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class UserRepository
{
    use UpdateFromDTO;

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    protected function queryById($userId): Builder
    {
        return $this->user->newQuery()
            ->where('id', '=', $userId);
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
        return $this->user->where('email', '=', $email)->first();
    }

    public function update(int $userId, UpdateUserDTO $dto): Response
    {
        $user = $this->queryById($userId)->first();

        $updatedSuccessfully = $this->updateFromDto($user, $dto);

        if (!$updatedSuccessfully) {
            return ResponseHelper::internalError();
        }

        return ResponseHelper::successResponse();
    }
}
