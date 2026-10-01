<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\ValidationException;

class ProjectLevel extends BaseModel
{
    protected $fillable = [
        'project_id',
        'project_asset_id',
        'code',
        'name',
        'elevation',
        'sort_order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'elevation' => 'decimal:2',
            'status' => ActiveStatus::class,
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ProjectLevel $level): void {
            $assetProjectId = ProjectAsset::query()->whereKey($level->project_asset_id)->value('project_id');

            if ((int) $assetProjectId !== (int) $level->project_id) {
                throw ValidationException::withMessages([
                    'project_asset_id' => 'The selected asset does not belong to this project.',
                ]);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(ProjectAsset::class, 'project_asset_id');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ProjectLocation::class);
    }
}
