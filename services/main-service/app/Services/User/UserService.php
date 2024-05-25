<?php

namespace App\Services\User;

use App\DTO\UpdateUserDTO;
use App\Exceptions\UnprocessableContentException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserDataResource;
use App\Http\Resources\User\UserProfileResource;
use App\Models\User;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
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
    private $userRepository;
    private $tagRepository;
    private $userSubscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TeamServiceInterface $teamService,
        PostServiceInterface $postService,
        UserRepositoryInterface $userRepository,
        TagRepositoryInterface $tagRepository,
        UserSubscriptionRepositoryInterface $userSubscriptionRepository,
    ) {
        $this->teamService = $teamService;
        $this->postService = $postService;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
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
        $userData = $this->userRepository->getById($user->id);
        $userData->canSubscribe =
            // Не сам пользователь
            $user->id !== $this->authorizedUserId
            // Нет подписки
            && empty($this->userSubscriptionRepository->getByBothIds($this->authorizedUserId, $user->id));


        $userData->tags = $this->tagRepository->getByUserId($userData->id);
        $userData->subscribersCount = count($this->userSubscriptionRepository->subscribers($userData->id));
        $userData->subscriptionsCount = count($this->userSubscriptionRepository->subscriptions($userData->id));
        $userData->teams = $this->teamService->user($userData->id);
        $userData->posts = $this->postService->user($userData->id);

        return new UserProfileResource(
            $userData
        );
    }

    /**
     * @param UpdateUserRequest $request
     * 
     * @return Response
     */
    public function update(UpdateUserRequest $request): Response
    {
        $this->validateRequestEmail($request->email, $this->authorizedUserId);
        $updateUserDTO = $this->createDTO($request, UpdateUserDTO::class);

        $updatedSuccessfully = $this->userRepository->update($this->authorizedUserId, $updateUserDTO);
        if (!$updatedSuccessfully) {
            return ResponseHelper::internalError();
        }

        return ResponseHelper::ok();
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
}
