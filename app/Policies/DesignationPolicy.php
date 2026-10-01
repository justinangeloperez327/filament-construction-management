<?php

namespace App\Policies;

use App\Models\Designation;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class DesignationPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'designations';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Designation $designation): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Designation $designation): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, Designation $designation): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
