<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserDataResource;
use App\Exceptions\UnprocessableContentException;
use App\DTO\User\UpdateUserDTO;

class UserService implements UserServiceInterface
{
    private $tagRepository;
    private $userRepository;
    private $subscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TagRepositoryInterface $tagRepository,
        UserRepositoryInterface $userRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
        $this->tagRepository = $tagRepository;
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $authorizedUser = Auth::user();
        Log::debug('Getting authorized user data', [
            'userId' => $authorizedUser->id
        ]);

        return new UserDataResource($authorizedUser);
    }

    public function show(User $user): JsonResource
    {
        Log::debug('Stated getting user profile data', [
            'userId' => $user->id
        ]);

        $user = $this->userRepository->getById($user->id);
        $user->canSubscribe =
            // Не сам пользователь
            $user->id !== $this->authorizedUserId
            // Нет подписки
            && !$this->subscriptionRepository->isSubscribedToUser($this->authorizedUserId, $user->id);


        $user = $this->assembleUserProfile($user);

        Log::debug('Got user profile data', [
            'userId' => $user->id
        ]);

        return new UserResource($user);
    }

    public function search(string $query): JsonResource
    {
        $users = $this->userRepository->search($query);

        return UserDataResource::collection($users);
    }

    public function update(UpdateUserDTO $updateUserDTO): void
    {
        $this->validateRequestEmail($updateUserDTO->email, $this->authorizedUserId);

        $this->userRepository->update($this->authorizedUserId, $updateUserDTO);
    }

    /**
     * @param string $email
     * @param int $authorizedUserId
     * 
     * @return void
     * 
     * @throws UnprocessableContentException
     */
    protected function validateRequestEmail(string $email, int $authorizedUserId): void
    {
        $userByEmail = $this->userRepository->getByEmail($email);
        $emailIsAvailable = $userByEmail === null || $userByEmail->id == $authorizedUserId;

        Log::debug('Checking if email is available for updating user profile', [
            'email' => $email,
            'userId' => $authorizedUserId,
            'isAvailable' => $emailIsAvailable
        ]);

        if (!$emailIsAvailable) {
            throw new UnprocessableContentException("This email is already taken");
        }
    }

    protected function assembleUserProfile(User $user): User
    {
        Log::debug('Assembling user profile data', [
            'userId' => $user->id
        ]);

        $this->setUserTags($user);
        $this->setUserCounters($user);

        Log::debug('Ended assembling user profile data', [
            'userId' => $user->id
        ]);

        return $user;
    }

    protected function setUserTags(User &$user): void
    {
        $user->tags = $this->tagRepository->getByEntityId($user->id, config('entities.user'));
    }

    protected function setUserCounters(User &$user): void
    {
        $user->subscribersCount = count($this->subscriptionRepository->getUserSubscribers($user->id));
        $user->subscriptionsCount = count($this->subscriptionRepository->getUserSubscriptions($user->id));
    }
}
