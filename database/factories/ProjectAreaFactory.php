<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Project;
use App\Models\ProjectArea;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectAreaFactory extends Factory
{
    protected $model = ProjectArea::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'code' => Str::upper(fake()->unique()->bothify('AREA-##')),
            'name' => 'Area '.fake()->unique()->numberBetween(1, 999),
            'description' => null,
            'sort_order' => 0,
            'status' => ActiveStatus::Active,
        ];
    }
}
