<?php

namespace App\Services\User;

use App\Exceptions\UnprocessableContentException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\User\AddUserInterestRequest;
use App\Models\UserInterest;
use App\Repositories\User\Interfaces\UserInterestRepositoryInterface;
use App\Repositories\UserInterestRepository;
use App\Services\User\Interfaces\UserInterestServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserInterestService implements UserInterestServiceInterface
{
    private $userInterestRepository;
    private ?int $authorizedUserId;

    public function __construct(
        UserInterestRepositoryInterface $userInterestRepository,
    ) {
        $this->userInterestRepository = $userInterestRepository;
        $this->authorizedUserId = Auth::id();
    }

    /**
     * @param AddUserInterestRequest $request
     * 
     * @return Response
     */
    public function add(AddUserInterestRequest $request): Response
    {
        $userInterest = $this->userInterestRepository->getByUserAndTitle(
            $this->authorizedUserId,
            $request->title
        );

        if (!empty($userInterest)) {
            throw new UnprocessableContentException('You already have this interest');
        }

        $this->userInterestRepository->add($this->authorizedUserId, $request->title);
        return ResponseHelper::created();
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
