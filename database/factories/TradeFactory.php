<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Discipline;
use App\Models\Trade;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TradeFactory extends Factory
{
    protected $model = Trade::class;

    public function definition(): array
    {
        return [
            'discipline_id' => Discipline::factory(),
            'code' => Str::upper(fake()->unique()->bothify('T-###')),
            'name' => Str::title(fake()->unique()->words(2, true)),
            'description' => null,
            'status' => ActiveStatus::Active,
        ];
    }
}
