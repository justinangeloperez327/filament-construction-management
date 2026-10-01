<?php

namespace App\Models;

use App\Enums\CompanyAddressType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyAddress extends BaseModel
{
    protected $fillable = [
        'company_id',
        'type',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'is_primary',
    ];

    protected function casts(): array
    {
        return [
            'type' => CompanyAddressType::class,
            'is_primary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (CompanyAddress $address): void {
            if (! $address->is_primary) {
                return;
            }

            static::query()
                ->where('company_id', $address->company_id)
                ->whereKeyNot($address->getKey())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
