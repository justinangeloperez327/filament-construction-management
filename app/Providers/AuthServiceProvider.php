<?php

namespace App\Providers;

use App\Listeners\RecordSuccessfulLogin;
use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanyContact;
use App\Models\CompanyDocument;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Permission;
use App\Models\Project;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use App\Policies\CompanyAddressPolicy;
use App\Policies\CompanyContactPolicy;
use App\Policies\CompanyDocumentPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\RolePolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Role::class, RolePolicy::class);
        Gate::policy(Permission::class, PermissionPolicy::class);
        Gate::policy(Team::class, TeamPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(Designation::class, DesignationPolicy::class);
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(CompanyContact::class, CompanyContactPolicy::class);
        Gate::policy(CompanyAddress::class, CompanyAddressPolicy::class);
        Gate::policy(CompanyDocument::class, CompanyDocumentPolicy::class);
        Gate::policy(Project::class, ProjectPolicy::class);

        Gate::before(
            fn (User $user): ?bool => $user->isSystemAdministrator() ? true : null,
        );

        Event::listen(Login::class, RecordSuccessfulLogin::class);
    }
}
