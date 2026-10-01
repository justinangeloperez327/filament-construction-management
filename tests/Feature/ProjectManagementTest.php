<?php

namespace Tests\Feature;

use App\Enums\ProjectStakeholderRole;
use App\Models\Company;
use App\Models\Project;
use App\Models\ProjectCompany;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AccessControlSeeder::class);
    }

    public function test_project_can_have_project_scoped_members(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'site-engineer')->firstOrFail();

        ProjectMember::factory()->for($project)->for($user)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->assertTrue($project->fresh()->activeMemberships->contains('user_id', $user->getKey()));
        $this->assertTrue(Gate::forUser($user)->allows('view', $project));
        $this->assertFalse(Gate::forUser($user)->allows('update', $project));
    }

    public function test_project_manager_can_update_only_assigned_project(): void
    {
        $assigned = Project::factory()->create();
        $other = Project::factory()->create();
        $manager = User::factory()->create();
        $role = Role::query()->where('slug', 'project-manager')->firstOrFail();

        ProjectMember::factory()->for($assigned)->for($manager)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->assertTrue(Gate::forUser($manager)->allows('update', $assigned));
        $this->assertFalse(Gate::forUser($manager)->allows('view', $other));
    }

    public function test_visible_to_scope_only_returns_assigned_projects_for_project_member(): void
    {
        $assigned = Project::factory()->create();
        Project::factory()->create();

        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'site-engineer')->firstOrFail();

        ProjectMember::factory()->for($assigned)->for($user)->create([
            'role_id' => $role->getKey(),
        ]);

        $projects = Project::query()->visibleTo($user)->get();

        $this->assertCount(1, $projects);
        $this->assertTrue($projects->first()->is($assigned));
    }

    public function test_primary_stakeholder_is_unique_per_project_role(): void
    {
        $project = Project::factory()->create();

        $first = ProjectCompany::factory()->for($project)->create([
            'company_id' => Company::factory(),
            'role' => ProjectStakeholderRole::Client,
            'is_primary' => true,
        ]);

        $second = ProjectCompany::factory()->for($project)->create([
            'company_id' => Company::factory(),
            'role' => ProjectStakeholderRole::Client,
            'is_primary' => true,
        ]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
        $this->assertSame($second->company->legal_name, $project->fresh()->load('stakeholders.company')->clientName());
    }

    public function test_system_administrator_can_manage_all_projects(): void
    {
        $project = Project::factory()->create();
        $admin = User::factory()->create();
        $admin->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $this->assertTrue(Gate::forUser($admin)->allows('view', $project));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $project));
        $this->assertTrue(Gate::forUser($admin)->allows('delete', $project));
    }
}
