<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\AddUserInterestRequest;
use App\Models\UserInterest;
use App\Services\User\UserInterestService;
use Illuminate\Http\Request;

class UserInterestController extends Controller
{
    private $userInterestService;

    public function __construct(UserInterestService $userInterestService)
    {
        $this->userInterestService = $userInterestService;
    }

    public function add(AddUserInterestRequest $request)
    {
        return $this->handleServiceCall(function () use ($request) {
            return $this->userInterestService->add($request);
        });
    }

    public function remove(UserInterest $userInterest)
    {
        return $this->handleServiceCall(function () use ($userInterest) {
            return $this->userInterestService->remove($userInterest);
        });
    }
}
