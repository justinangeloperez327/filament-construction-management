<?php

namespace App\Models;

use App\Enums\CompanyDocumentType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyDocument extends BaseModel
{
    protected $fillable = [
        'company_id',
        'type',
        'title',
        'document_number',
        'issued_at',
        'expires_at',
        'file_path',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'type' => CompanyDocumentType::class,
            'issued_at' => 'date',
            'expires_at' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? false;
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        if (! $this->expires_at || $this->isExpired()) {
            return false;
        }

        return $this->expires_at->lte(today()->addDays($days));
    }
}
