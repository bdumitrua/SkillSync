<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Repositories\NotificationRepository;
use App\Models\Post;
use App\Models\Like;
use App\Enums\NotificationType;
use App\DTO\CreateNotificationDTO;

class NotifyAboutPostLike implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Post $post;
    public Like $like;

    /**
     * Create a new job instance.
     */
    public function __construct(Post $post, Like $like)
    {
        $this->post = $post;
        $this->like = $like;
        $this->queue = 'notifications';
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationRepository $notificationRepository): void
    {
        Log::debug('Starting NotifyAboutLike job', [
            'postId' => $this->post->id,
            'likeId' => $this->like->id
        ]);

        if ($this->post->author_type !== config('entities.user')) {
            Log::debug('Notifications are being created only for users');
        }

        $newNotificationDto = CreateNotificationDTO::create()
            ->setReceiverId($this->post->author_id)
            ->setType(NotificationType::PostLike)
            ->setFromWho($this->like->user_id, config('entities.user'))
            ->setToWhat($this->post->id, config('entities.post'));

        $notificationRepository->create($newNotificationDto);
    }
}
