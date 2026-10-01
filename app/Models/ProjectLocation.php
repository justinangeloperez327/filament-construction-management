<?php

namespace App\Models;

use App\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class ProjectLocation extends BaseModel
{
    protected $fillable = [
        'project_id',
        'project_level_id',
        'code',
        'name',
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
        static::saving(function (ProjectLocation $location): void {
            $levelProjectId = ProjectLevel::query()->whereKey($location->project_level_id)->value('project_id');

            if ((int) $levelProjectId !== (int) $location->project_id) {
                throw ValidationException::withMessages([
                    'project_level_id' => 'The selected level does not belong to this project.',
                ]);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(ProjectLevel::class, 'project_level_id');
    }
}
