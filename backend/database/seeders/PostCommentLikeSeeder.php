<?php

namespace Database\Seeders;

use App\Models\PostCommentLike;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostCommentLikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PostCommentLike::factory(150)->create();
    }
}
