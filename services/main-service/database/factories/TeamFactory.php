<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::all()->random();

        return [
            'admin_id' => $user->id,
            'name' => $this->faker->words(2, true),
            'avatar' => $this->faker->url(),
            'description' => $this->faker->words(10, true),
            'email' => $this->faker->email(),
            'site' => $this->faker->url(),
            'chat_id' => null,
        ];
    }
}
