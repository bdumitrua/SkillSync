<?php

namespace App\Services;

use App\DTO\User\CreateSubscriptionDTO;
use App\Exceptions\SubscriptionException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\Interfaces\SubscriptionServiceInterface;
use App\Models\Subscription;
use App\Http\Requests\CreateSubscriptionRequest;
use App\Http\Resources\Team\TeamDataResource;
use App\Http\Resources\User\UserDataResource;
use App\Repositories\Interfaces\SubscriptionRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Traits\CreateDTO;

class SubscriptionService implements SubscriptionServiceInterface
{
    use CreateDTO;

    private $teamRepository;
    private $userRepository;
    private $subscriptionRepository;
    private ?int $authorizedUserId;

    public function __construct(
        TeamRepositoryInterface $teamRepository,
        UserRepositoryInterface $userRepository,
        SubscriptionRepositoryInterface $subscriptionRepository,
    ) {
        $this->teamRepository = $teamRepository;
        $this->userRepository = $userRepository;
        $this->subscriptionRepository = $subscriptionRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function users(int $userId): JsonResource
    {
        $userIds = $this->subscriptionRepository->getUsersByUserId($userId)
            ->pluck('entity_id')->toArray();
        $usersData = $this->userRepository->getByIds($userIds);

        return UserDataResource::collection($usersData);
    }

    public function teams(int $userId): JsonResource
    {
        $teamIds = $this->subscriptionRepository->getTeamsByUserId($userId)
            ->pluck('entity_id')->toArray();
        $teamsData = $this->teamRepository->getByIds($teamIds);

        return TeamDataResource::collection($teamsData);
    }

    public function create(CreateSubscriptionRequest $request): void
    {
        $this->patchCreateSubscriptionRequest($request);
        Gate::authorize(
            SUBSCRIBE_TO_ENTITY_GATE,
            [Subscription::class, $request->entityType, $request->entityId]
        );

        /** @var CreateSubscriptionDTO */
        $createSubscriptionDTO = $this->createDTO($request, CreateSubscriptionDTO::class);
        $createSubscriptionDTO->userId = $this->authorizedUserId;

        $subscribed = $this->subscriptionRepository->create($createSubscriptionDTO);

        if (!$subscribed) {
            throw new SubscriptionException("You're already subscribed.");
        }
    }

    public function delete(Subscription $subscription): void
    {
        Gate::authorize(
            UNSUBSCRIBE_FROM_ENTITY_GATE,
            [Subscription::class, $subscription->entity_type, $subscription->entity_id]
        );

        $this->subscriptionRepository->delete($subscription);
    }

    protected function patchCreateSubscriptionRequest(CreateSubscriptionRequest &$request): void
    {
        $entitiesPath = config('entities');

        $request->merge([
            'entityType' => $entitiesPath[$request->entityType]
        ]);
    }
}
