<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyContact extends BaseModel
{
    protected $fillable = [
        'company_id',
        'name',
        'job_title',
        'department',
        'email',
        'mobile',
        'office_phone',
        'is_primary',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'status' => ActiveStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (CompanyContact $contact): void {
            if (! $contact->is_primary) {
                return;
            }

            static::query()
                ->where('company_id', $contact->company_id)
                ->whereKeyNot($contact->getKey())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
