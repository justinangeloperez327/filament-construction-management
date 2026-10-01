<?php

namespace App\Models;

use App\Enums\ProjectStakeholderRole;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectCompany extends BaseModel
{
    protected $fillable = [
        'project_id',
        'company_id',
        'role',
        'is_primary',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'role' => ProjectStakeholderRole::class,
            'is_primary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (ProjectCompany $stakeholder): void {
            if (! $stakeholder->is_primary) {
                return;
            }

            static::query()
                ->where('project_id', $stakeholder->project_id)
                ->where('role', $stakeholder->role->value)
                ->whereKeyNot($stakeholder->getKey())
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
