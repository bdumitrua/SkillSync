<?php

namespace App\Services;

use App\DTO\UpdateUserDTO;
use App\Exceptions\UnprocessableContentException;
use App\Helpers\ResponseHelper;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserDataResource;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use App\Repository\UserInterestRepository;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use App\Services\External\PostsService;
use App\Services\External\TeamsService;
use App\Traits\CreateDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    use CreateDTO;

    private TeamsService $teamsService;
    private PostsService $postsService;
    private UserRepository $userRepository;
    private UserInterestRepository $userInterestRepository;
    private UserSubscriptionRepository $userSubscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TeamsService $teamsService,
        PostsService $postsService,
        UserRepository $userRepository,
        UserInterestRepository $userInterestRepository,
        UserSubscriptionRepository $userSubscriptionRepository,
    ) {
        $this->teamsService = $teamsService;
        $this->postsService = $postsService;
        $this->userRepository = $userRepository;
        $this->userInterestRepository = $userInterestRepository;
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

        $userData->interests = $this->userInterestRepository->getByUserId($userData->id);
        $userData->subscribersCount = count($this->userSubscriptionRepository->subscribers($userData->id));
        $userData->subscriptionsCount = count($this->userSubscriptionRepository->subscriptions($userData->id));
        $userData->teams = $this->teamsService->getTeamsByUserId($userData->id);
        $userData->posts = $this->postsService->getPostsByUserId($userData->id);

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
