<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Services\Interfaces\TagServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Models\User;
use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserDataResource;
use App\Exceptions\UnprocessableContentException;
use App\DTO\User\UpdateUserDTO;

class UserService implements UserServiceInterface
{
    private $teamService;
    private $postService;
    private $tagService;
    private $userRepository;
    private $subscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TeamServiceInterface $teamService,
        PostServiceInterface $postService,
        TagServiceInterface $tagService,
        UserRepositoryInterface $userRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
        $this->teamService = $teamService;
        $this->postService = $postService;
        $this->tagService = $tagService;
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

    public function search(Request $request): JsonResource
    {
        $users = $this->userRepository->search($request->input('query'));

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

        $user->tags = $this->tagService->user($user->id);
        $user->teams = $this->teamService->user($user->id);
        $user->posts = $this->postService->user($user->id);

        Log::debug('Counting user subscribers and subscriptions number');
        $user->subscribersCount = count($this->subscriptionRepository->getUserSubscribers($user->id));
        $user->subscriptionsCount = count($this->subscriptionRepository->getUserSubscriptions($user->id));

        Log::debug('Ended assembling user profile data', [
            'userId' => $user->id
        ]);

        return $user;
    }
}
