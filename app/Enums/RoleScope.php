<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum RoleScope: string
{
    use ProvidesOptions;

    case Global = 'global';
    case Project = 'project';

    public function label(): string
    {
        return match ($this) {
            self::Global => 'Global',
            self::Project => 'Project',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Global => 'info',
            self::Project => 'success',
        };
    }
}
