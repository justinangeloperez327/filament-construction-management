<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('projects.view_any')
            || $user->projectMemberships()->active()->exists();
    }

    public function view(User $user, Project $project): bool
    {
        return $project->userHasPermission($user, 'projects.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('projects.create');
    }

    public function update(User $user, Project $project): bool
    {
        return $project->userHasPermission($user, 'projects.update');
    }

    public function delete(User $user, Project $project): bool
    {
        return $project->userHasPermission($user, 'projects.delete');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermission('projects.delete');
    }

    public function restore(User $user, Project $project): bool
    {
        return $project->userHasPermission($user, 'projects.restore');
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $project->userHasPermission($user, 'projects.force_delete');
    }
}
