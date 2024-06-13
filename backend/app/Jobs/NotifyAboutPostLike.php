<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Repositories\NotificationRepository;
use App\Models\PostLike;
use App\Models\Post;
use App\Enums\NotificationType;
use App\DTO\CreateNotificationDTO;

class NotifyAboutPostLike implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Post $post;
    public PostLike $postLike;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post, PostLike $postLike)
    {
        $this->post = $post;
        $this->postLike = $postLike;
        $this->queue = 'notifications';
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationRepository $notificationRepository): void
    {
        Log::debug('Starting NotifyAboutPostLike job', [
            'postId' => $this->post->id,
            'postLikeId' => $this->postLike->id
        ]);

        if ($this->post->entity_type !== config('entities.user')) {
            Log::debug('Notifications are being created only for users');
        }

        $newNotificationDto = CreateNotificationDTO::create()
            ->setReceiverId($this->post->entity_id)
            ->setType(NotificationType::PostLike)
            ->setFromWho($this->postLike->user_id, config('entities.user'))
            ->setToWhat($this->post->id, config('entities.post'));

        $notificationRepository->create($newNotificationDto);
    }
}
