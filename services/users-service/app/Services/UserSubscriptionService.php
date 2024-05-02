<?php

namespace App\Services;

use App\Http\Resources\UserSubscriptionResoruce;
use App\Models\User;
use App\Repository\UserRepository;
use App\Repository\UserSubscriptionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserSubscriptionService
{
    private UserSubscriptionRepository $userSubscriptionRepository;
    private UserRepository $userRepository;
    private ?int $authorizedUserId;

    public function __construct(
        UserSubscriptionRepository $userSubscriptionRepository,
        UserRepository $userRepository,
    ) {
        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->userRepository = $userRepository;
        $this->authorizedUserId = Auth::id();
    }

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function subscribers(User $user): JsonResource
    {
        $subscribersId = $this->userSubscriptionRepository->subscribers($user->id);
        $subscribersData = $this->assemblyUsersData($subscribersId);

        return UserSubscriptionResoruce::collection($subscribersData);
    }

    /**
     * @param User $user
     * 
     * @return JsonResource
     */
    public function subscriptions(User $user): JsonResource
    {
        $subscribedsIds = $this->userSubscriptionRepository->subscriptions($user->id);
        $subscribedsData = $this->assemblyUsersData($subscribedsIds);

        return UserSubscriptionResoruce::collection($subscribedsData);
    }

    /**
     * @param User $user
     * 
     * @return Response
     */
    public function subscribe(User $user): Response
    {
        return $this->userSubscriptionRepository->subscribe($this->authorizedUserId, $user->id);
    }

    /**
     * @param User $user
     * 
     * @return Response
     */
    public function unsubscribe(User $user): Response
    {
        return $this->userSubscriptionRepository->unsubscribe($this->authorizedUserId, $user->id);
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
