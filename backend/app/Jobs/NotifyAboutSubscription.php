<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Repositories\NotificationRepository;
use App\Models\Subscription;
use App\Enums\NotificationType;
use App\DTO\CreateNotificationDTO;

class NotifyAboutSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Subscription $subscription;

    /**
     * Create a new job instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->queue = 'notifications';
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationRepository $notificationRepository): void
    {
        Log::debug('Starting NotifyAboutSubscription job', [
            'subscription' => $this->subscription->toArray(),
        ]);

        if ($this->subscription->entity_type !== config('entities.user')) {
            Log::debug('Notifications are being created only for users');
        }

        $newNotificationDto = CreateNotificationDTO::create()
            ->setReceiverId($this->subscription->entity_id)
            ->setType(NotificationType::Subscription)
            ->setFromWho($this->subscription->subscriber_id, config('entities.user'))
            ->setToWhat($this->subscription->entity_id, config('entities.user'));

        $notificationRepository->create($newNotificationDto);
    }
}
