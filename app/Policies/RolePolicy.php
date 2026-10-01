<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class RolePolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'roles';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Role $role): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Role $role): bool
    {
        return ! $role->is_system && $this->allowed($user, 'update');
    }

    public function delete(User $user, Role $role): bool
    {
        return ! $role->is_system && $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
