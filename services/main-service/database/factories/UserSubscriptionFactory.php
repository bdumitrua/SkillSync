<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserSubscriptionFactory extends Factory
{
    public function definition(): array
    {
        $subscriber = User::all()->random();
        $subscribed = User::where('id', '!=', $subscriber->id)->get()->random();

        return [
            'subscriber_id' => $subscriber->id,
            'subscribed_id' => $subscribed->id,
        ];
    }
}
