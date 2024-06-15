<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Repositories\NotificationRepository;
use App\Models\PostComment;
use App\Models\Like;
use App\Enums\NotificationType;
use App\DTO\CreateNotificationDTO;

class NotifyAboutCommentLike implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PostComment $postComment;
    public Like $like;

    /**
     * Create a new job instance.
     */
    public function __construct(PostComment $postComment, Like $like)
    {
        $this->postComment = $postComment;
        $this->like = $like;
        $this->queue = 'notifications';
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationRepository $notificationRepository): void
    {
        Log::debug('Starting NotifyAboutCommentLike job', [
            'postCommentId' => $this->postComment->id,
            'likeId' => $this->like->id
        ]);

        $newNotificationDto = CreateNotificationDTO::create()
            ->setReceiverId($this->postComment->user_id)
            ->setType(NotificationType::CommentLike)
            ->setFromWho($this->like->user_id, config('entities.user'))
            ->setToWhat($this->postComment->id, config('entities.postComment'));

        $notificationRepository->create($newNotificationDto);
    }
}
