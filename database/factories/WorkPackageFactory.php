<?php

namespace Database\Factories;

use App\Enums\GeneralStatus;
use App\Models\Project;
use App\Models\WorkPackage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class WorkPackageFactory extends Factory
{
    protected $model = WorkPackage::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'code' => Str::upper(fake()->unique()->bothify('WP-###')),
            'title' => Str::title(fake()->words(3, true)),
            'description' => null,
            'discipline_id' => null,
            'trade_id' => null,
            'contractor_company_id' => null,
            'start_date' => null,
            'end_date' => null,
            'status' => GeneralStatus::Draft,
        ];
    }
}
