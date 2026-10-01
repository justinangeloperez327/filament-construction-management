<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class DepartmentPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'departments';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Department $department): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Department $department): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, Department $department): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
