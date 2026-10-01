<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectArea;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\ProjectStructureSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentProjectStructureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AccessControlSeeder::class,
            ProjectStructureSeeder::class,
        ]);
    }

    public function test_system_administrator_can_open_discipline_and_trade_resources(): void
    {
        $admin = User::factory()->create();
        $admin->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $this->actingAs($admin);

        $this->get('/admin/disciplines')->assertOk();
        $this->get('/admin/trades')->assertOk();
    }

    public function test_project_manager_can_open_project_with_structure_relation_managers(): void
    {
        $project = Project::factory()->create();
        ProjectArea::factory()->for($project)->create();

        $manager = User::factory()->create();
        $role = Role::query()->where('slug', 'project-manager')->firstOrFail();

        ProjectMember::factory()->for($project)->for($manager)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->actingAs($manager);

        $this->get("/admin/projects/{$project->getKey()}")->assertOk();
        $this->get("/admin/projects/{$project->getKey()}/edit")->assertOk();
    }

    public function test_regular_project_member_cannot_edit_project(): void
    {
        $project = Project::factory()->create();
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'site-engineer')->firstOrFail();

        ProjectMember::factory()->for($project)->for($user)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->actingAs($user);

        $this->get("/admin/projects/{$project->getKey()}")->assertOk();
        $this->get("/admin/projects/{$project->getKey()}/edit")->assertForbidden();
    }
}
