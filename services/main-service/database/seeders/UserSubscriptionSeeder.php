<?php

namespace Database\Seeders;

use App\Models\TeamVacancy;
use App\Models\UserSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserSubscription::factory(15)->create();
    }
}
