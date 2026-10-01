<?php

namespace Database\Factories;

use App\Enums\CompanyAddressType;
use App\Models\Company;
use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyAddressFactory extends Factory
{
    protected $model = CompanyAddress::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'type' => CompanyAddressType::Office,
            'address_line_1' => fake()->streetAddress(),
            'address_line_2' => null,
            'city' => fake()->city(),
            'state' => fake()->state(),
            'postal_code' => fake()->postcode(),
            'country' => fake()->country(),
            'is_primary' => false,
        ];
    }
}
