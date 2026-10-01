<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum GeneralStatus: string
{
    use ProvidesOptions;

    case Draft = 'draft';
    case Active = 'active';
    case Inactive = 'inactive';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
            self::Archived => 'Archived',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft, self::Inactive, self::Archived => 'gray',
            self::Active => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
        };
    }
}
