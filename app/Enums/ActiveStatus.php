<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum ActiveStatus: string
{
    use ProvidesOptions;

    case Active = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'gray',
        };
    }
}
