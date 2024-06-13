<?php

namespace App\Observers;

use Illuminate\Support\Facades\Log;
use App\Models\Subscription;
use App\Jobs\NotifyAboutSubscription;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        Log::debug("Handling 'created' method in SubscriptionObserver", [
            'subscription' => $subscription->toArray()
        ]);

        NotifyAboutSubscription::dispatch($subscription);
    }

    /**
     * Handle the Subscription "deleted" event.
     */
    public function deleted(Subscription $subscription): void
    {
        //
    }
}
