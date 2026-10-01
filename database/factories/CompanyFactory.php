<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'legal_name' => $name,
            'trading_name' => fake()->optional()->company(),
            'code' => Str::upper(fake()->unique()->bothify('CMP-####')),
            'registration_number' => fake()->optional()->numerify('REG-########'),
            'vat_number' => fake()->optional()->numerify('TRN-###############'),
            'phone' => fake()->optional()->phoneNumber(),
            'email' => fake()->optional()->companyEmail(),
            'website' => fake()->optional()->url(),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'status' => ActiveStatus::Active,
            'notes' => null,
        ];
    }
}
