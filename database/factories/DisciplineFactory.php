<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Discipline;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DisciplineFactory extends Factory
{
    protected $model = Discipline::class;

    public function definition(): array
    {
        $name = fake()->unique()->word();

        return [
            'code' => Str::upper(fake()->unique()->bothify('D-###')),
            'name' => Str::title($name),
            'description' => null,
            'status' => ActiveStatus::Active,
        ];
    }
}
