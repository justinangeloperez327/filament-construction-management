<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    public function definition(): array
    {
        $name = fake()->unique()->jobTitle();

        return [
            'department_id' => null,
            'name' => $name,
            'code' => Str::upper(fake()->unique()->bothify('DSG-###??')),
            'description' => null,
            'status' => ActiveStatus::Active,
        ];
    }
}
