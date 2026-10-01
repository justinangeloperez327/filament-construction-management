<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Project;
use App\Models\ProjectArea;
use App\Models\ProjectAsset;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectAssetFactory extends Factory
{
    protected $model = ProjectAsset::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'project_area_id' => null,
            'code' => Str::upper(fake()->unique()->bothify('BLD-###')),
            'name' => 'Building '.fake()->unique()->numberBetween(1, 999),
            'asset_type' => 'Building',
            'description' => null,
            'sort_order' => 0,
            'status' => ActiveStatus::Active,
        ];
    }

    public function forArea(ProjectArea $area): static
    {
        return $this->state(fn (): array => [
            'project_id' => $area->project_id,
            'project_area_id' => $area->getKey(),
        ]);
    }
}
