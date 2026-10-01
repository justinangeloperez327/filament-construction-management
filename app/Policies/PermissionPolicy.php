<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class PermissionPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'permissions';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Permission $permission): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Permission $permission): bool
    {
        return ! $permission->is_system && $this->allowed($user, 'update');
    }

    public function delete(User $user, Permission $permission): bool
    {
        return ! $permission->is_system && $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
