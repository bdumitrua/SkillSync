<?php

namespace App\Repositories;

use App\Models\UserInterest;
use App\Repositories\User\Interfaces\UserInterestRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserInterestRepository implements UserInterestRepositoryInterface
{
    private UserInterest $userInterest;

    public function __construct(
        UserInterest $userInterest,
    ) {
        $this->userInterest = $userInterest;
    }

    public function getByUserId(int $userId): Collection
    {
        return $this->userInterest->where('user_id', '=', $userId)->get();
    }

    public function getByUserAndTitle(int $userId, string $title): ?UserInterest
    {
        return $this->userInterest
            ->where('user_id', '=', $userId)
            ->where('title', '=', $title)
            ->first();
    }

    public function add(int $userId, string $title): void
    {
        $this->userInterest->create([
            'user_id' => $userId,
            'title' => $title
        ]);
    }

    public function remove(UserInterest $userInterest): void
    {
        $userInterest->delete();
    }
}
