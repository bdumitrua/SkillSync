<?php

namespace App\Services\User;

use App\Helpers\ResponseHelper;
use App\Http\Resources\User\UserSubscriptionResoruce;
use App\Models\User;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\User\Interfaces\UserSubscriptionRepositoryInterface;
use App\Services\User\Interfaces\UserSubscriptionServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UserSubscriptionService implements UserSubscriptionServiceInterface
{
    private $userSubscriptionRepository;
    private $userRepository;
    private ?int $authorizedUserId;

    public function __construct(
        UserSubscriptionRepositoryInterface $userSubscriptionRepository,
        UserRepositoryInterface $userRepository,
    ) {
        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->userRepository = $userRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function subscribers(User $user): JsonResource
    {
        $subscribersId = $this->userSubscriptionRepository->subscribers($user->id);
        $subscribersData = $this->assemblyUsersData($subscribersId);

        return UserSubscriptionResoruce::collection($subscribersData);
    }

    public function subscriptions(User $user): JsonResource
    {
        $subscribedsIds = $this->userSubscriptionRepository->subscriptions($user->id);
        $subscribedsData = $this->assemblyUsersData($subscribedsIds);

        return UserSubscriptionResoruce::collection($subscribedsData);
    }

    public function subscribe(User $user): void
    {
        Gate::authorize(SUBSCRIBE_ON_USER_GATE, $user->id);

        $this->userSubscriptionRepository->subscribe($this->authorizedUserId, $user->id);
    }

    public function unsubscribe(User $user): void
    {
        Gate::authorize(UNSUBSCRIBE_FROM_USER_GATE, $user->id);

        $this->userSubscriptionRepository->unsubscribe($this->authorizedUserId, $user->id);
    }

    /**
     * @param array $usersIds
     * 
     * @return Collection
     */
    protected function assemblyUsersData(array $usersIds): Collection
    {
        $usersData = $this->userRepository->getByIds($usersIds);
        $authorizedUserSubscriptions = $this->userSubscriptionRepository->subscriptions($this->authorizedUserId);

        foreach ($usersData as $userData) {
            $userData->canSubscribe =
                // Не подписан
                !in_array($userData->id, $authorizedUserSubscriptions)
                // Не сам пользователь
                && $userData->id !== $this->authorizedUserId;
        }

        return $usersData;
    }
}
