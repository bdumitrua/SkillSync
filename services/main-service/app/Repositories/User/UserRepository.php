<?php

namespace App\Repositories\User;

use App\DTO\User\UpdateUserDTO;
use App\Helpers\ResponseHelper;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\GetCachedData;
use App\Traits\UpdateFromDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface
{
    use UpdateFromDTO, GetCachedData;

    protected function queryById($userId): Builder
    {
        return User::query()->where('id', '=', $userId);
    }

    public function getById(int $userId): ?User
    {
        Log::debug('Getting user by id', [
            'userId' => $userId
        ]);

        $cacheKey = $this->getUserCacheKey($userId);
        return $this->getCachedData($cacheKey, CACHE_TIME_USER_DATA, function () use ($userId) {
            return $this->queryById($userId)->first();
        });
    }

    public function getByIds(array $usersIds): Collection
    {
        Log::debug('Getting users by ids', [
            'usersIds' => $usersIds
        ]);

        return $this->getCachedCollection($usersIds, function ($userId) {
            return $this->getById($userId);
        });
    }

    public function getByEmail(string $email): ?User
    {
        Log::debug('Getting user by email', [
            'email' => $email
        ]);

        return User::where('email', '=', $email)->first();
    }

    function search(string $query): Collection
    {
        return User::search($query);
    }

    public function update(int $userId, UpdateUserDTO $dto): bool
    {
        Log::debug('Started update user data', [
            'userId' => $userId
        ]);

        $user = $this->queryById($userId)->first();

        $this->updateFromDto($user, $dto);
        $this->clearUserCache($user->id);

        Log::debug('User data succesfully updated', [
            'userId' => $userId
        ]);

        return true;
    }

    protected function getUserCacheKey(int $userId): string
    {
        return CACHE_KEY_USER_DATA . $userId;
    }

    protected function clearUserCache(int $userId): void
    {
        $this->clearCache($this->getUserCacheKey($userId));
    }
}
