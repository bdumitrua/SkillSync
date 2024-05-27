<?php

namespace Database\Factories;

use App\Models\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostCommentLike>
 */
class PostCommentLikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();
        $postComment = PostComment::all()->random();

        return [
            'user_id' => $user->id,
            'post_comment_id' => $postComment->id,
        ];
    }
}
