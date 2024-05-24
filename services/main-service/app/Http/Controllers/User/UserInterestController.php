<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\User\Interfaces\UserInterestServiceInterface;
use App\Models\UserInterest;
use App\Http\Requests\User\AddUserInterestRequest;
use App\Http\Controllers\Controller;

class UserInterestController extends Controller
{
    private $userInterestService;

    public function __construct(UserInterestServiceInterface $userInterestService)
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
