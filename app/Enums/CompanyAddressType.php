<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum CompanyAddressType: string
{
    use ProvidesOptions;

    case Registered = 'registered';
    case Office = 'office';
    case Billing = 'billing';
    case Warehouse = 'warehouse';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Registered => 'Registered',
            self::Office => 'Office',
            self::Billing => 'Billing',
            self::Warehouse => 'Warehouse',
            self::Other => 'Other',
        };
    }
}
