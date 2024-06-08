<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Events\NewSubscriptionEvent;

class SubscriptionObserver
{
    /**
     * Handle the Subscription "created" event.
     */
    public function created(Subscription $subscription): void
    {
        event(new NewSubscriptionEvent($subscription));
    }

    /**
     * Handle the Subscription "deleted" event.
     */
    public function deleted(Subscription $subscription): void
    {
        //
    }
}
