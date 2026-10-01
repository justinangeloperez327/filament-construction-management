<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 year', '+3 months');
        $plannedFinish = fake()->dateTimeBetween($start, '+3 years');

        return [
            'project_number' => Str::upper(fake()->unique()->bothify('PRJ-####')),
            'name' => fake()->unique()->words(4, true),
            'short_name' => Str::upper(fake()->unique()->bothify('P-###')),
            'description' => fake()->optional()->paragraph(),
            'project_type' => fake()->randomElement(['Building', 'Infrastructure', 'Industrial', 'Fit-Out']),
            'location' => fake()->streetAddress(),
            'country' => 'United Arab Emirates',
            'city' => fake()->randomElement(['Abu Dhabi', 'Dubai', 'Al Ain']),
            'contract_value' => fake()->randomFloat(2, 1000000, 500000000),
            'currency' => 'AED',
            'start_date' => $start,
            'planned_completion_date' => $plannedFinish,
            'actual_completion_date' => null,
            'defects_liability_end_date' => null,
            'planned_progress' => fake()->randomFloat(2, 0, 100),
            'actual_progress' => fake()->randomFloat(2, 0, 100),
            'status' => ProjectStatus::Active,
        ];
    }
}
