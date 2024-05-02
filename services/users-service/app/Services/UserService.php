<?php

namespace App\Services;

use App\DTO\UpdateUserDTO;
use App\Exceptions\UnprocessableContentException;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserDataResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Repository\UserRepository;
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
    private ?int $authorizedUserId;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        return new UserProfileResource(
            $this->userRepository->getById($user->id)
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
