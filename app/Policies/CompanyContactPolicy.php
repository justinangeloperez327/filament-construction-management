<?php

namespace App\Policies;

use App\Models\CompanyContact;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class CompanyContactPolicy
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

    public function view(User $user, CompanyContact $contact): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, CompanyContact $contact): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, CompanyContact $contact): bool
    {
        return $this->allowed($user, 'delete');
    }
}
