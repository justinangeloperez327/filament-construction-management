<?php

namespace Tests\Feature;

use App\Enums\ActiveStatus;
use App\Models\LoginActivity;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Filament\Facades\Filament;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AccessControlSeeder::class);
    }

    public function test_access_control_seed_creates_system_roles_and_permissions(): void
    {
        $this->assertDatabaseHas('roles', [
            'slug' => 'system-administrator',
            'scope' => 'global',
            'is_system' => true,
        ]);

        $this->assertDatabaseHas('roles', [
            'slug' => 'project-manager',
            'scope' => 'project',
            'is_system' => true,
        ]);

        $this->assertDatabaseHas('permissions', [
            'slug' => 'users.view_any',
            'is_system' => true,
        ]);
    }

    public function test_system_administrator_receives_all_permissions(): void
    {
        $user = User::factory()->create();
        $role = Role::query()->where('slug', 'system-administrator')->firstOrFail();

        $user->roles()->attach($role);

        $this->assertTrue($user->isSystemAdministrator());
        $this->assertTrue($user->hasPermission('users.delete'));
        $this->assertTrue(Gate::forUser($user)->allows('viewAny', User::class));
    }

    public function test_permission_is_inherited_from_global_role(): void
    {
        $permission = Permission::query()->where('slug', 'users.view_any')->firstOrFail();
        $role = Role::factory()->create(['scope' => 'global']);
        $role->permissions()->attach($permission);

        $user = User::factory()->create();
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermission('users.view_any'));
        $this->assertTrue(Gate::forUser($user)->allows('viewAny', User::class));
    }

    public function test_inactive_user_cannot_access_filament_panel(): void
    {
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $user = User::factory()->inactive()->create();

        $this->assertSame(ActiveStatus::Inactive, $user->status);
        $this->assertFalse($user->canAccessPanel(Filament::getCurrentPanel()));
    }

    public function test_successful_login_is_recorded(): void
    {
        Event::fakeExcept([Login::class]);

        $user = User::factory()->create();

        event(new Login('web', $user, false));

        $this->assertNotNull($user->fresh()->last_login_at);
        $this->assertTrue(LoginActivity::query()->whereBelongsTo($user)->exists());
    }
}
