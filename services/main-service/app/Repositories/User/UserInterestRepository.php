<?php

namespace App\Repositories\User;

use App\Models\UserInterest;
use App\Repositories\User\Interfaces\UserInterestRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserInterestRepository implements UserInterestRepositoryInterface
{
    public function getByUserId(int $userId): Collection
    {
        return UserInterest::where('user_id', '=', $userId)->get();
    }

    public function getByUserAndTitle(int $userId, string $title): ?UserInterest
    {
        return UserInterest::where('user_id', '=', $userId)
            ->where('title', '=', $title)
            ->first();
    }

    public function add(int $userId, string $title): void
    {
        UserInterest::create([
            'user_id' => $userId,
            'title' => $title
        ]);
    }

    public function remove(UserInterest $userInterest): void
    {
        $userInterest->delete();
    }
}
