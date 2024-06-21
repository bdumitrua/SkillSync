<?php

namespace Database\Factories;

use Ramsey\Uuid\Type\Integer;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $authorTypes = [User::class, Team::class];
        $authorType = $authorTypes[rand(0, 1)];
        $author = $authorType::all()->random();

        return [
            'text' => $this->faker->words(10, true),
            'media_url' => $this->faker->url(),
            'author_id' => $author->id,
            'author_type' => $authorType,
        ];
    }
}
