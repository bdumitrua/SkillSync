<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserTableSeeder::class,
            UserSubscriptionSeeder::class,
            TeamSeeder::class,
            TeamMemberSeeder::class,
            TeamLinkSeeder::class,
            TeamVacancySeeder::class,
            TeamApplicationSeeder::class,
            PostSeeder::class,
            PostLikeSeeder::class,
            PostCommentSeeder::class,
            PostCommentLikeSeeder::class,
            TagSeeder::class,
        ]);
    }
}
