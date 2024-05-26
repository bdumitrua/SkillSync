<?php

namespace App\Services\User;

use App\DTO\User\UpdateUserDTO;
use App\Exceptions\UnprocessableContentException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Post\Interfaces\PostServiceInterface;
use App\Services\Team\Interfaces\TeamServiceInterface;
use App\Services\User\Interfaces\UserServiceInterface;
use App\Traits\CreateDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceInterface
{
    use CreateDTO;

    private $teamService;
    private $postService;
    private $tagService;
    private $userRepository;
    private $userSubscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TeamServiceInterface $teamService,
        PostServiceInterface $postService,
        TagServiceInterface $tagService,
        UserRepositoryInterface $userRepository,
        UserSubscriptionRepositoryInterface $userSubscriptionRepository,
    ) {
        $this->teamService = $teamService;
        $this->postService = $postService;
        $this->tagService = $tagService;
        $this->userRepository = $userRepository;
        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    /**
     * @return JsonResource
     */
    public function index(): JsonResource
    {
        return new UserDataResource(
            Auth::user()
        );
    }

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function show(User $user): JsonResource
    {
        $user = $this->userRepository->getById($user->id);
        $user->canSubscribe =
            // Не сам пользователь
            $user->id !== $this->authorizedUserId
            // Нет подписки
            && !$this->userSubscriptionRepository->userIsSubscribedToUser($this->authorizedUserId, $user->id);


        $user = $this->assembleUserProfile($user);

        return new UserResource($user);
    }

    /**
     * @param UpdateUserRequest $request
     * 
     * @return void
     */
    public function update(UpdateUserRequest $request): void
    {
        $this->validateRequestEmail($request->email, $this->authorizedUserId);
        $updateUserDTO = $this->createDTO($request, UpdateUserDTO::class);

        $updatedSuccessfully = $this->userRepository->update($this->authorizedUserId, $updateUserDTO);
    }

    /**
     * @param string $email
     * @param int $authorizedUserId
     * 
     * @return void
     */
    protected function validateRequestEmail(string $email, int $authorizedUserId): void
    {
        $userByEmail = $this->userRepository->getByEmail($email);
        $emailIsAvailable = $userByEmail === null || $userByEmail->id == $authorizedUserId;

        if (!$emailIsAvailable) {
            throw new UnprocessableContentException("This email is already taken");
        }
    }

    protected function assembleUserProfile(User $user): User
    {
        $user->tags = $this->tagService->user($user->id);
        $user->teams = $this->teamService->user($user->id);
        $user->posts = $this->postService->user($user->id);
        $user->subscribersCount = count($this->userSubscriptionRepository->subscribers($user->id));
        $user->subscriptionsCount = count($this->userSubscriptionRepository->subscriptions($user->id));

        return $user;
    }
}
