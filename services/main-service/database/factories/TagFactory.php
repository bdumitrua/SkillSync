<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;
use App\Models\Post;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $entityTypes = [User::class, Team::class, Post::class];
        $entityType = $entityTypes[rand(0, 2)];
        $entityId = $entityType::all()->random();

        return [
            'entity_id' => $entityId,
            'entity_type' => $entityType,
            'title' => $this->faker->word(),
        ];
    }
}
