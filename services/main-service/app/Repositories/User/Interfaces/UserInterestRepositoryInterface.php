<?php

namespace App\Repositories\User\Interfaces;

use App\Models\UserInterest;
use Illuminate\Database\Eloquent\Collection;

interface UserInterestRepositoryInterface
{
    /**
     * @param int $userId
     * 
     * @return Collection
     */
    function getByUserId(int $userId): Collection;

    /**
     * @param int $userId
     * @param string $title
     * 
     * @return UserInterest|null
     */
    function getByUserAndTitle(int $userId, string $title): ?UserInterest;

    /**
     * @param int $userId
     * @param string $title
     * 
     * @return void
     */
    function add(int $userId, string $title): void;

    /**
     * @param UserInterest $userInterest
     * 
     * @return void
     */
    function remove(UserInterest $userInterest): void;
}
