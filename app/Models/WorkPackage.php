<?php

namespace App\Models;

use App\Enums\GeneralStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\ValidationException;

class WorkPackage extends BaseModel
{
    protected $fillable = [
        'project_id',
        'code',
        'title',
        'description',
        'discipline_id',
        'trade_id',
        'contractor_company_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'status' => GeneralStatus::class,
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (WorkPackage $workPackage): void {
            if (! $workPackage->trade_id || ! $workPackage->discipline_id) {
                return;
            }

            $tradeDisciplineId = Trade::query()->whereKey($workPackage->trade_id)->value('discipline_id');

            if ((int) $tradeDisciplineId !== (int) $workPackage->discipline_id) {
                throw ValidationException::withMessages([
                    'trade_id' => 'The selected trade does not belong to the selected discipline.',
                ]);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }

    public function trade(): BelongsTo
    {
        return $this->belongsTo(Trade::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'contractor_company_id');
    }
}
