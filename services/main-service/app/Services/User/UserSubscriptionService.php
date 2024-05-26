<?php

namespace App\Services\User;

use App\Exceptions\SubscriptionException;
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

    /**
     * @throws SubscriptionException
     */
    public function subscribe(User $user): void
    {
        Gate::authorize(SUBSCRIBE_ON_USER_GATE, [User::class, $user->id]);

        $subscribed = $this->userSubscriptionRepository->subscribe($this->authorizedUserId, $user->id);
        if (!$subscribed) {
            throw new SubscriptionException("You're already subscribed to this user.");
        }
    }

    /**
     * @throws SubscriptionException
     */
    public function unsubscribe(User $user): void
    {
        Gate::authorize(UNSUBSCRIBE_FROM_USER_GATE, [User::class, $user->id]);

        $unsubscribed = $this->userSubscriptionRepository->unsubscribe($this->authorizedUserId, $user->id);
        if (!$unsubscribed) {
            throw new SubscriptionException("You're not subscribed to this user.");
        }
    }

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
