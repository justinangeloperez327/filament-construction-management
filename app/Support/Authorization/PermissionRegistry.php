<?php

namespace App\Support\Authorization;

use Illuminate\Support\Str;

final class PermissionRegistry
{
    private const ACTIONS = [
        'view_any' => 'View Any',
        'view' => 'View',
        'create' => 'Create',
        'update' => 'Update',
        'delete' => 'Delete',
        'restore' => 'Restore',
        'force_delete' => 'Force Delete',
        'approve' => 'Approve',
        'export' => 'Export',
    ];

    private const RESOURCES = [
        'Administration' => [
            'users',
            'roles',
            'permissions',
            'teams',
            'departments',
            'designations',
            'settings',
        ],
        'Companies' => ['companies'],
        'Projects' => ['projects'],
        'Documents' => ['documents', 'drawings'],
        'Engineering' => ['rfis', 'submittals'],
        'QA/QC' => ['qaqc'],
        'HSE' => ['hse'],
        'Site Operations' => ['tasks', 'daily_reports', 'manpower', 'equipment', 'materials'],
        'Procurement' => ['procurement'],
        'Commercial' => ['contracts', 'commercial'],
        'Reports' => ['reports'],
    ];

    public static function all(): array
    {
        $permissions = [];

        foreach (self::RESOURCES as $group => $resources) {
            foreach ($resources as $resource) {
                foreach (self::ACTIONS as $action => $actionLabel) {
                    $slug = "{$resource}.{$action}";

                    $permissions[$slug] = [
                        'name' => $actionLabel.' '.Str::headline($resource),
                        'slug' => $slug,
                        'group' => $group,
                        'description' => null,
                    ];
                }
            }
        }

        return $permissions;
    }

    public static function slugsForResources(array $resources, array $actions = []): array
    {
        $allowedActions = $actions !== [] ? $actions : array_keys(self::ACTIONS);

        return collect(self::all())
            ->filter(function (array $permission) use ($resources, $allowedActions): bool {
                [$resource, $action] = explode('.', $permission['slug'], 2);

                return in_array($resource, $resources, true)
                    && in_array($action, $allowedActions, true);
            })
            ->keys()
            ->values()
            ->all();
    }
}
