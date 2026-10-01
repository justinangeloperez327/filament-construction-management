<?php

namespace App\Policies;

use App\Models\Discipline;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class DisciplinePolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'disciplines';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Discipline $discipline): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Discipline $discipline): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, Discipline $discipline): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
