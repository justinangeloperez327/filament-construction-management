<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'legal_name',
        'trading_name',
        'code',
        'registration_number',
        'vat_number',
        'phone',
        'email',
        'website',
        'country',
        'city',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }

    public function types(): BelongsToMany
    {
        return $this->belongsToMany(CompanyType::class)->withTimestamps();
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(CompanyContact::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CompanyAddress::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(CompanyDocument::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function primaryContact(): HasMany
    {
        return $this->hasMany(CompanyContact::class)->where('is_primary', true);
    }
}
