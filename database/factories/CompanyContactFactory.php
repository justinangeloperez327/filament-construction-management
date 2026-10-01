<?php

namespace Database\Factories;

use App\Enums\ActiveStatus;
use App\Models\Company;
use App\Models\CompanyContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyContactFactory extends Factory
{
    protected $model = CompanyContact::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'name' => fake()->name(),
            'job_title' => fake()->jobTitle(),
            'department' => fake()->optional()->word(),
            'email' => fake()->safeEmail(),
            'mobile' => fake()->phoneNumber(),
            'office_phone' => fake()->optional()->phoneNumber(),
            'is_primary' => false,
            'status' => ActiveStatus::Active,
            'notes' => null,
        ];
    }
}
