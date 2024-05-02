<?php

namespace App\Repository;

use App\Helpers\ResponseHelper;
use App\Models\UserInterest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UserInterestRepository
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

    /**
     * @param int $userId
     * @param string $title
     * 
     * @return UserInterest|null
     */
    public function getByUserAndTitle(int $userId, string $title): ?UserInterest
    {
        return $this->userInterest
            ->where('user_id', '=', $userId)
            ->where('title', '=', $title)
            ->first();
    }

    /**
     * @param int $userId
     * @param string $title
     * 
     * @return void
     */
    public function add(int $userId, string $title): void
    {
        $this->userInterest->create([
            'user_id' => $userId,
            'title' => $title
        ]);
    }

    /**
     * @param UserInterest $userInterest
     * 
     * @return void
     */
    public function remove(UserInterest $userInterest): void
    {
        $userInterest->delete();
    }
}
