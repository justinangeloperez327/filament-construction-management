<?php

namespace Tests\Feature;

use App\Enums\CompanyDocumentType;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanyContact;
use App\Models\CompanyDocument;
use App\Models\CompanyType;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\AccessControlSeeder;
use Database\Seeders\CompanyDirectorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CompanyDirectoryTest extends TestCase
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

    public function test_company_can_have_multiple_classifications(): void
    {
        $company = Company::factory()->create();
        $types = CompanyType::query()
            ->whereIn('slug', ['contractor', 'supplier'])
            ->pluck('id');

        $company->types()->sync($types);

        $this->assertCount(2, $company->fresh()->types);
    }

    public function test_only_one_primary_contact_is_retained_per_company(): void
    {
        $company = Company::factory()->create();

        $first = CompanyContact::factory()->for($company)->create(['is_primary' => true]);
        $second = CompanyContact::factory()->for($company)->create(['is_primary' => true]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
    }

    public function test_only_one_primary_address_is_retained_per_company(): void
    {
        $company = Company::factory()->create();

        $first = CompanyAddress::factory()->for($company)->create(['is_primary' => true]);
        $second = CompanyAddress::factory()->for($company)->create(['is_primary' => true]);

        $this->assertFalse($first->fresh()->is_primary);
        $this->assertTrue($second->fresh()->is_primary);
    }

    public function test_company_document_expiry_helpers_work(): void
    {
        $company = Company::factory()->create();

        $expired = CompanyDocument::factory()->for($company)->create([
            'type' => CompanyDocumentType::Insurance,
            'expires_at' => today()->subDay(),
        ]);

        $soon = CompanyDocument::factory()->for($company)->create([
            'expires_at' => today()->addDays(20),
        ]);

        $this->assertTrue($expired->isExpired());
        $this->assertTrue($soon->isExpiringSoon());
    }

    public function test_system_administrator_can_manage_companies(): void
    {
        $user = User::factory()->create();
        $user->roles()->attach(
            Role::query()->where('slug', 'system-administrator')->firstOrFail(),
        );

        $this->assertTrue(Gate::forUser($user)->allows('viewAny', Company::class));
        $this->assertTrue(Gate::forUser($user)->allows('create', Company::class));
    }

    public function test_user_can_be_associated_with_company(): void
    {
        $company = Company::factory()->create();
        $user = User::factory()->for($company)->create();

        $this->assertTrue($user->company->is($company));
        $this->assertTrue($company->users->contains($user));
    }
}
