<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostComment>
 */
class PostCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        $post = Post::all()->random();

        return [
            'user_id' => $user->id,
            'post_id' => $post->id,
            'text' => $this->faker->words(3, true),
            'media_url' => $this->faker->url(),
        ];
    }
}
