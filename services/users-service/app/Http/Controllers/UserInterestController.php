<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddUserInterestRequest;
use App\Models\UserInterest;
use App\Services\UserInterestService;
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
