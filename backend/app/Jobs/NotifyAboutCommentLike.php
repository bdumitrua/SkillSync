<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Repositories\NotificationRepository;
use App\Models\PostCommentLike;
use App\Models\PostComment;
use App\Enums\NotificationType;
use App\DTO\CreateNotificationDTO;

class NotifyAboutCommentLike implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public PostComment $postComment;
    public PostCommentLike $postCommentLike;

    /**
     * Create a new job instance.
     */
    public function __construct(PostComment $postComment, PostCommentLike $postCommentLike)
    {
        $this->postComment = $postComment;
        $this->postCommentLike = $postCommentLike;
        $this->queue = 'notifications';
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationRepository $notificationRepository): void
    {
        Log::debug('Starting NotifyAboutCommentLike job', [
            'postCommentId' => $this->postComment->id,
            'postCommentLikeId' => $this->postCommentLike->id
        ]);

        $newNotificationDto = CreateNotificationDTO::create()
            ->setReceiverId($this->postComment->user_id)
            ->setType(NotificationType::CommentLike)
            ->setFromWho($this->postCommentLike->user_id, config('entities.user'))
            ->setToWhat($this->postComment->id, config('entities.postComment'));

        $notificationRepository->create($newNotificationDto);
    }
}
