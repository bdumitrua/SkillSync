<?php

namespace App\Services\User\Interfaces;

use App\Http\Requests\User\AddUserInterestRequest;
use App\Models\UserInterest;
use Illuminate\Http\Response;

interface UserInterestServiceInterface
{
    /**
     * @param AddUserInterestRequest $request
     * 
     * @return Response
     */
    public function add(AddUserInterestRequest $request): Response;

    /**
     * @param UserInterest $userInterest
     * 
     * @return void
     */
    public function remove(UserInterest $userInterest): void;
}
