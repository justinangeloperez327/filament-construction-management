<?php

namespace App\Filament\Resources\Projects\RelationManagers\Concerns;

use App\Models\Project;

trait AuthorizesProjectRelation
{
    protected function canProject(string $permission): bool
    {
        $user = auth()->user();
        $project = $this->getOwnerRecord();

        return $user !== null
            && $project instanceof Project
            && $project->userHasPermission($user, $permission);
    }
}
