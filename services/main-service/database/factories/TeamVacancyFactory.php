<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamVacancy>
 */
class TeamVacancyFactory extends Factory
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
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->words(50, true),
            'team_id' => $team->id,
        ];
    }
}
