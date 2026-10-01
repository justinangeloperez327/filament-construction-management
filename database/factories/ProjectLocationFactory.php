<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\ProjectLevel;
use App\Models\ProjectLocation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectLocationFactory extends Factory
{
    protected $model = ProjectLocation::class;

    public function definition(): array
    {
        return [
            'project_id' => null,
            'project_level_id' => null,
            'code' => Str::upper(fake()->unique()->bothify('LOC-###')),
            'name' => 'Location '.fake()->unique()->numberBetween(1, 999),
            'description' => null,
            'sort_order' => 0,
            'status' => ActiveStatus::Active,
        ];
    }

    public function forLevel(ProjectLevel $level): static
    {
        return $this->state(fn (): array => [
            'project_id' => $level->project_id,
            'project_level_id' => $level->getKey(),
        ]);
    }
}
