<?php

namespace App\Enums;

use App\Enums\Concerns\ProvidesOptions;

enum ProjectStakeholderRole: string
{
    use ProvidesOptions;

    case Client = 'client';
    case Consultant = 'consultant';
    case MainContractor = 'main_contractor';
    case Contractor = 'contractor';
    case Subcontractor = 'subcontractor';
    case Supplier = 'supplier';
    case GovernmentEntity = 'government_entity';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Client => 'Client',
            self::Consultant => 'Consultant',
            self::MainContractor => 'Main Contractor',
            self::Contractor => 'Contractor',
            self::Subcontractor => 'Subcontractor',
            self::Supplier => 'Supplier',
            self::GovernmentEntity => 'Government Entity',
            self::Other => 'Other',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Client => 'info',
            self::Consultant => 'warning',
            self::MainContractor => 'success',
            self::Contractor, self::Subcontractor => 'gray',
            self::Supplier => 'primary',
            self::GovernmentEntity => 'info',
            self::Other => 'gray',
        };
    }
}
