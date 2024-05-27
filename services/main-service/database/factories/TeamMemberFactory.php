<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Team;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team = Team::all()->random();
        $user = User::all()->random();

        return [
            'user_id' => $user->id,
            'team_id' => $team->id,
            'is_moderator' => false,
            'about' => $this->faker->words(7, true),
        ];
    }
}
