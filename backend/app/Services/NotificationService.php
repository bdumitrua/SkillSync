<?php

namespace App\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Database\Eloquent\Collection;
use App\Services\Interfaces\NotificationServiceInterface;
use App\Repositories\User\Interfaces\UserRepositoryInterface;
use App\Repositories\Team\Interfaces\TeamRepositoryInterface;
use App\Repositories\Project\Interfaces\ProjectRepositoryInterface;
use App\Repositories\Post\Interfaces\PostRepositoryInterface;
use App\Repositories\Post\Interfaces\PostCommentRepositoryInterface;
use App\Repositories\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use App\Http\Resources\NotificationResource;

class NotificationService implements NotificationServiceInterface
{
    protected $userRepository;
    protected $teamRepository;
    protected $postRepository;
    protected $projectRepository;
    protected $postCommentRepository;
    protected $notificationRepository;
    protected ?int $authorizedUserId;

    public function __construct(
        PostRepositoryInterface $postRepository,
        UserRepositoryInterface $userRepository,
        TeamRepositoryInterface $teamRepository,
        ProjectRepositoryInterface $projectRepository,
        PostCommentRepositoryInterface $postCommentRepository,
        NotificationRepositoryInterface $notificationRepository,
    ) {
        $this->postRepository = $postRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->projectRepository = $projectRepository;
        $this->postCommentRepository = $postCommentRepository;
        $this->notificationRepository = $notificationRepository;
        $this->authorizedUserId = Auth::id();
    }

    public function index(): JsonResource
    {
        $notifications = $this->notificationRepository->getByUserId($this->authorizedUserId);
        $notifications = $this->assembleNotificationsData($notifications);

        return NotificationResource::collection($notifications);
    }

    public function seen(Notification $notification): void
    {
        Gate::authorize('update', [Notification::class, $notification]);

        $this->notificationRepository->seen($notification);
    }

    public function delete(Notification $notification): void
    {
        Gate::authorize('delete', [Notification::class, $notification]);

        $this->notificationRepository->delete($notification);
    }

    protected function assembleNotificationsData(Collection $notifications): Collection
    {
        $typesRepositories = [
            config('entities.user') => $this->userRepository,
            config('entities.team') => $this->teamRepository,
            config('entities.post') => $this->postRepository,
            config('entities.postComment') => $this->postCommentRepository,
            config('entities.project') => $this->projectRepository,
        ];

        foreach ($notifications as $notification) {
            $notification->fromData = $typesRepositories[$notification->from_type]->getById($notification->from_id);
            $notification->toData = $typesRepositories[$notification->to_type]->getById($notification->to_id);
        }

        return $notifications;
    }
}
