<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\CompanyType;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\CompanyDirectorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilamentCompanyResourceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            AccessControlSeeder::class,
            CompanyDirectorySeeder::class,
        ]);
    }

    public function test_system_administrator_can_open_company_resource(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $company = Company::factory()->create();
        $company->types()->attach(
            CompanyType::query()->where('slug', 'contractor')->firstOrFail(),
        );

        $this->actingAs($user);

        $this->get('/admin/companies')->assertOk();
        $this->get("/admin/companies/{$company->getKey()}/edit")->assertOk();
    }

    public function test_user_without_company_permission_cannot_open_company_resource(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/companies')->assertForbidden();
    }
}
