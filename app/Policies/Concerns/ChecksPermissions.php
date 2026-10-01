<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ChecksPermissions
{
    protected function allowed(User $user, string $action): bool
    {
        return $user->hasPermission($this->permissionPrefix().'.'.$action);
    }

    abstract protected function permissionPrefix(): string;
}
