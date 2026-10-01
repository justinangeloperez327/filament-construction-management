<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
        ];
    }

    public function designations(): HasMany
    {
        return $this->hasMany(Designation::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
