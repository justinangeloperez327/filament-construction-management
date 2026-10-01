<?php

namespace App\Models;

use App\Enums\ProjectStakeholderRole;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'project_number',
        'name',
        'short_name',
        'description',
        'project_type',
        'location',
        'country',
        'city',
        'contract_value',
        'currency',
        'start_date',
        'planned_completion_date',
        'actual_completion_date',
        'defects_liability_end_date',
        'planned_progress',
        'actual_progress',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'contract_value' => 'decimal:2',
            'start_date' => 'date',
            'planned_completion_date' => 'date',
            'actual_completion_date' => 'date',
            'defects_liability_end_date' => 'date',
            'planned_progress' => 'decimal:2',
            'actual_progress' => 'decimal:2',
            'status' => ProjectStatus::class,
        ];
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function stakeholders(): HasMany
    {
        return $this->hasMany(ProjectCompany::class);
    }

    public function projectContacts(): HasMany
    {
        return $this->hasMany(ProjectContact::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(ProjectArea::class);
    }

    public function assets(): HasMany
    {
        return $this->hasMany(ProjectAsset::class);
    }

    public function levels(): HasMany
    {
        return $this->hasMany(ProjectLevel::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ProjectLocation::class);
    }

    public function workPackages(): HasMany
    {
        return $this->hasMany(WorkPackage::class);
    }

    public function activeMemberships(): HasMany
    {
        return $this->memberships()->active();
    }

    public function scopeVisibleTo(Builder $query, User $user): Builder
    {
        if ($user->hasPermission('projects.view_any')) {
            return $query;
        }

        return $query->whereHas(
            'memberships',
            fn (Builder $membershipQuery): Builder => $membershipQuery
                ->active()
                ->where('user_id', $user->getKey()),
        );
    }

    public function userHasPermission(User $user, string $permission): bool
    {
        if ($user->hasPermission($permission)) {
            return true;
        }

        return $this->memberships()
            ->active()
            ->where('user_id', $user->getKey())
            ->whereHas(
                'role.permissions',
                fn (Builder $query): Builder => $query->where('slug', $permission),
            )
            ->exists();
    }

    public function clientName(): ?string
    {
        $clients = $this->stakeholders
            ->filter(fn (ProjectCompany $stakeholder): bool => $stakeholder->role === ProjectStakeholderRole::Client);

        return ($clients->firstWhere('is_primary', true) ?? $clients->first())
            ?->company
            ?->legal_name;
    }

    public function projectManagerNames(): string
    {
        return $this->memberships
            ->filter(fn (ProjectMember $membership): bool => $membership->isActive())
            ->filter(fn (ProjectMember $membership): bool => $membership->role?->slug === 'project-manager')
            ->pluck('user.name')
            ->filter()
            ->implode(', ');
    }
}
