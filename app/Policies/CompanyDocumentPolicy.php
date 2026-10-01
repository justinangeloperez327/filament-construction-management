<?php

namespace App\Policies;

use App\Models\CompanyDocument;
use App\Models\User;
use App\Policies\Concerns\ChecksPermissions;

class CompanyDocumentPolicy
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

    public function view(User $user, CompanyDocument $document): bool
    {
        return $this->allowed($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->allowed($user, 'create');
    }

    public function update(User $user, CompanyDocument $document): bool
    {
        return $this->allowed($user, 'update');
    }

    public function delete(User $user, CompanyDocument $document): bool
    {
        return $this->allowed($user, 'delete');
    }
}
