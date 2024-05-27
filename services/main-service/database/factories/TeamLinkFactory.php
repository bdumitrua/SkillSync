<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamLink>
 */
class TeamLinkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team = Team::all()->random();

        return [
            'team_id' => $team->id,
            'name' => $this->faker->words(2, true),
            'url' => $this->faker->url(),
            'text' => $this->faker->word(),
            'is_private' => $this->faker->boolean(50),
            'icon_type' => null,
        ];
    }
}
