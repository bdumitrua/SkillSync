<?php

namespace Database\Factories;

use App\Enums\TeamApplicationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\TeamVacancy;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamApplication>
 */
class TeamApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team = Team::all()->random();
        $teamVacancy = TeamVacancy::all()->random();
        $user = User::all()->random();

        return [
            'user_id' => $user->id,
            'vacancy_id' => $teamVacancy->id,
            'team_id' => $team->id,
            'text' => $this->faker->words(30, true),
            'status' => TeamApplicationStatus::Sended,
        ];
    }
}
