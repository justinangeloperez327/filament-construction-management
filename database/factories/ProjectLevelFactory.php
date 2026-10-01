<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\ProjectAsset;
use App\Models\ProjectLevel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectLevelFactory extends Factory
{
    protected $model = ProjectLevel::class;

    public function definition(): array
    {
        return [
            'project_id' => null,
            'project_asset_id' => null,
            'code' => Str::upper(fake()->unique()->bothify('L-###')),
            'name' => 'Level '.fake()->unique()->numberBetween(1, 999),
            'elevation' => fake()->randomFloat(2, -5, 200),
            'sort_order' => 0,
            'status' => ActiveStatus::Active,
        ];
    }

    public function forAsset(ProjectAsset $asset): static
    {
        return $this->state(fn (): array => [
            'project_id' => $asset->project_id,
            'project_asset_id' => $asset->getKey(),
        ]);
    }
}
