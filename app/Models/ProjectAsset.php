<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class ProjectAsset extends BaseModel
{
    protected $fillable = [
        'project_id',
        'project_area_id',
        'code',
        'name',
        'asset_type',
        'description',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ActiveStatus::class,
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ProjectAsset $asset): void {
            if (! $asset->project_area_id) {
                return;
            }

            $areaProjectId = ProjectArea::query()->whereKey($asset->project_area_id)->value('project_id');

            if ((int) $areaProjectId !== (int) $asset->project_id) {
                throw ValidationException::withMessages([
                    'project_area_id' => 'The selected area does not belong to this project.',
                ]);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(ProjectArea::class, 'project_area_id');
    }

    public function levels(): HasMany
    {
        return $this->hasMany(ProjectLevel::class);
    }
}
