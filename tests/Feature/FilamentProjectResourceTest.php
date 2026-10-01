<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentProjectResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AccessControlSeeder::class);
    }

    public function test_system_administrator_can_open_project_resource(): void
    {
        $project = Project::factory()->create();
        $admin = User::factory()->create();
        $admin->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $this->actingAs($admin);

        $this->get('/admin/projects')->assertOk();
        $this->get("/admin/projects/{$project->getKey()}")->assertOk();
        $this->get("/admin/projects/{$project->getKey()}/edit")->assertOk();
    }

    public function test_project_member_can_open_assigned_project_but_not_another_project(): void
    {
        $assigned = Project::factory()->create();
        $other = Project::factory()->create();
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'site-engineer')->firstOrFail();

        ProjectMember::factory()->for($assigned)->for($user)->create([
            'role_id' => $role->getKey(),
        ]);

        $this->actingAs($user);

        $this->get('/admin/projects')->assertOk();
        $this->get("/admin/projects/{$assigned->getKey()}")->assertOk();
        $this->get("/admin/projects/{$other->getKey()}")->assertNotFound();
    }

    public function test_user_without_project_access_cannot_open_project_resource(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/projects')->assertForbidden();
    }
}
