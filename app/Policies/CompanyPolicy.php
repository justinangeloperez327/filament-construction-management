<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class CompanyPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'companies';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Company $company): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Company $company): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, Company $company): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function restore(User $user, Company $company): bool
    {
        return $this->allowed($user, 'restore');
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $this->allowed($user, 'force_delete');
    }
}
