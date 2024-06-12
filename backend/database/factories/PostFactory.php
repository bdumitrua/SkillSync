<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;
use Ramsey\Uuid\Type\Integer;

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
        $entityTypes = [User::class, Team::class];
        $entityType = $entityTypes[rand(0, 1)];
        $entity = $entityType::all()->random();

        return [
            'text' => $this->faker->words(10, true),
            'media_url' => $this->faker->url(),
            'entity_id' => $entity->id,
            'entity_type' => $entityType,
        ];
    }
}
