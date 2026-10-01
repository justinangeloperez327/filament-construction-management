<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentAdministrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AccessControlSeeder::class);
    }

    public function test_system_administrator_can_open_administration_resources(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $this->actingAs($user);

        $this->get('/admin/users')->assertOk();
        $this->get('/admin/roles')->assertOk();
        $this->get('/admin/permissions')->assertOk();
        $this->get('/admin/departments')->assertOk();
        $this->get('/admin/designations')->assertOk();
        $this->get('/admin/teams')->assertOk();
    }

    public function test_user_without_permission_cannot_open_user_resource(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/users')->assertForbidden();
    }
}
