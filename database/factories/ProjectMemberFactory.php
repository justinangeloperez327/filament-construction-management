<?php

namespace Database\Factories;

use App\Enums\RoleScope;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMemberFactory extends Factory
{
    protected $model = ProjectMember::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
            'role_id' => Role::factory()->state(['scope' => RoleScope::Project]),
            'started_at' => today(),
            'ended_at' => null,
            'is_active' => true,
            'notes' => null,
        ];
    }
}
