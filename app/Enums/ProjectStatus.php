<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum ProjectStatus: string
{
    use ProvidesOptions;

    case Planning = 'planning';
    case Mobilization = 'mobilization';
    case Active = 'active';
    case OnHold = 'on_hold';
    case SubstantiallyComplete = 'substantially_complete';
    case Completed = 'completed';
    case Closed = 'closed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Planning => 'Planning',
            self::Mobilization => 'Mobilization',
            self::Active => 'Active',
            self::OnHold => 'On Hold',
            self::SubstantiallyComplete => 'Substantially Complete',
            self::Completed => 'Completed',
            self::Closed => 'Closed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Planning => 'gray',
            self::Mobilization => 'info',
            self::Active => 'success',
            self::OnHold => 'warning',
            self::SubstantiallyComplete => 'info',
            self::Completed => 'success',
            self::Closed => 'gray',
            self::Cancelled => 'danger',
        };
    }
}
