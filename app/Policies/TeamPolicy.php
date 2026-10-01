<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class TeamPolicy
{
    use ChecksPermissions;

    protected function permissionPrefix(): string
    {
        return 'teams';
    }

    public function viewAny(User $user): bool
    {
        return $this->allowed($user, 'view_any');
    }

    public function view(User $user, Team $team): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, Team $team): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, Team $team): bool
    {
        return $this->allowed($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->allowed($user, 'delete');
    }
}
