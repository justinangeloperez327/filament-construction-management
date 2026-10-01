<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyType extends BaseModel
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }
}
