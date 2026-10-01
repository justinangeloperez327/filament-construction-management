<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum ApprovalStatus: string
{
    use ProvidesOptions;

    case Draft = 'draft';
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Returned = 'returned';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
            self::Returned => 'Returned',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft, self::Cancelled => 'gray',
            self::Pending => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Returned => 'info',
        };
    }
}
