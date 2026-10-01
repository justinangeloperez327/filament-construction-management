<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class UserPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'users';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, User $model): bool
    {
        return $user->is($model) || $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, User $model): bool
    {
        return $user->is($model) || $this->allowed($user, 'update');
    }

    public function delete(User $user, User $model): bool
    {
        return ! $user->is($model) && $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
