<?php

namespace App\Services;

use App\DTO\UpdateUserDTO;
use App\Exceptions\UnprocessableContentException;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserDataResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Repository\UserInterestRepository;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use App\Traits\CreateDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    use CreateDTO;

    private UserRepository $userRepository;
    private UserInterestRepository $userInterestRepository;
    private UserSubscriptionRepository $userSubscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        UserRepository $userRepository,
        UserInterestRepository $userInterestRepository,
        UserSubscriptionRepository $userSubscriptionRepository,
    ) {
        $this->userRepository = $userRepository;
        $this->userInterestRepository = $userInterestRepository;
        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        return new UserDataResource(
            Auth::user()
        );
    }

    public function show(User $user): JsonResource
    {
        $userData = $this->userRepository->getById($user->id);
        $userData->canSubscribe =
            // Не сам пользователь
            $user->id !== $this->authorizedUserId
            // Нет подписки
            && !$this->userSubscriptionRepository->existsByBothIds($this->authorizedUserId, $user->id);

        $userData->interests = $this->userInterestRepository->getByUserId($userData->id);

        return new UserProfileResource(
            $userData
        );
    }

    public function update(UpdateUserRequest $request): Response
    {
        $this->validateRequestEmail($request->email, $this->authorizedUserId);
        $updateUserDTO = $this->createDTO($request, UpdateUserDTO::class);

        return $this->userRepository->update($this->authorizedUserId, $updateUserDTO);
    }

    protected function validateRequestEmail(string $email, int $authorizedUserId): void
    {
        $userByEmail = $this->userRepository->getByEmail($email);
        $emailIsAvailable = $userByEmail === null || $userByEmail->id == $authorizedUserId;

        if (!$emailIsAvailable) {
            throw new UnprocessableContentException("This email is already taken");
        }
    }
}
