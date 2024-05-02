<?php

namespace App\Services;

use App\Exceptions\UnprocessableContentException;
use App\Http\Requests\AddUserInterestRequest;
use App\Models\UserInterest;
use App\Repository\UserInterestRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserInterestService
{
    private UserInterestRepository $userInterestRepository;
    private ?int $authorizedUserId;

    public function __construct(
        UserInterestRepository $userInterestRepository,
    ) {
        $this->userInterestRepository = $userInterestRepository;
        $this->authorizedUserId = Auth::id();
    }

    /**
     * @param AddUserInterestRequest $request
     * 
     * @return void
     */
    public function add(AddUserInterestRequest $request): void
    {
        $userInterest = $this->userInterestRepository->getByUserAndTitle(
            $this->authorizedUserId,
            $request->title
        );

        if (!empty($userInterest)) {
            throw new UnprocessableContentException('You already have this interest');
        }

        $this->userInterestRepository->add($this->authorizedUserId, $request->title);
    }

    /**
     * @param UserInterest $userInterest
     * 
     * @return void
     */
    public function remove(UserInterest $userInterest): void
    {
        $this->userInterestRepository->remove($userInterest);
    }
}
