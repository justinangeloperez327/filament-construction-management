<?php

namespace Database\Factories;

use App\Models\CompanyContact;
use App\Models\Project;
use App\Models\ProjectContact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectContactFactory extends Factory
{
    protected $model = ProjectContact::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'company_contact_id' => CompanyContact::factory(),
            'project_role' => fake()->jobTitle(),
            'is_primary' => false,
            'notes' => null,
        ];
    }
}
