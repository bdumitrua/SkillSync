<?php

namespace Database\Seeders;

use App\Models\TeamVacancy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamVacancySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeamVacancy::factory(15)->create();
    }
}
