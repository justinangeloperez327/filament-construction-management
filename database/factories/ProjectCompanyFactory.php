<?php

namespace Database\Factories;

use App\Enums\ProjectStakeholderRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectCompanyFactory extends Factory
{
    protected $model = ProjectCompany::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'company_id' => Company::factory(),
            'role' => ProjectStakeholderRole::Contractor,
            'is_primary' => false,
            'notes' => null,
        ];
    }
}
