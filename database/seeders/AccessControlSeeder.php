<?php

namespace Database\Seeders;

use App\Enums\RoleScope;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Permission;
use App\Models\Role;
use App\Support\Authorization\PermissionRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPermissions();
        $this->seedRoles();
        $this->seedOrganizationStructure();
    }

    private function seedPermissions(): void
    {
        foreach (PermissionRegistry::all() as $definition) {
            Permission::query()->updateOrCreate(
                ['slug' => $definition['slug']],
                [...$definition, 'is_system' => true],
            );
        }
    }

    private function seedRoles(): void
    {
        $roles = [
            ['System Administrator', RoleScope::Global, true],
            ['Management', RoleScope::Global, true],
            ['Project Manager', RoleScope::Project, true],
            ['Construction Manager', RoleScope::Project, true],
            ['Site Engineer', RoleScope::Project, true],
            ['Planning Engineer', RoleScope::Project, true],
            ['Quantity Surveyor', RoleScope::Project, true],
            ['Procurement Engineer', RoleScope::Project, true],
            ['Document Controller', RoleScope::Project, true],
            ['QA/QC Engineer', RoleScope::Project, true],
            ['HSE Engineer', RoleScope::Project, true],
            ['Foreman', RoleScope::Project, true],
            ['Client Representative', RoleScope::Project, true],
            ['Consultant', RoleScope::Project, true],
            ['Subcontractor', RoleScope::Project, true],
        ];

        foreach ($roles as [$name, $scope, $isSystem]) {
            Role::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'scope' => $scope,
                    'is_system' => $isSystem,
                    'description' => null,
                ],
            );
        }

        $systemAdmin = Role::query()->where('slug', 'system-administrator')->firstOrFail();
        $systemAdmin->permissions()->sync(Permission::query()->pluck('id'));

        $management = Role::query()->where('slug', 'management')->firstOrFail();
        $management->permissions()->sync(
            Permission::query()
                ->where(function ($query) {
                    $query->where('slug', 'like', '%.view')
                        ->orWhere('slug', 'like', '%.view_any')
                        ->orWhere('slug', 'like', '%.export')
                        ->orWhere('slug', 'like', '%.approve');
                })
                ->pluck('id'),
        );

        $projectViewSlugs = [
            'projects.view',
            'project_structure.view',
            'work_packages.view',
        ];

        $projectViewPermissionIds = Permission::query()
            ->whereIn('slug', $projectViewSlugs)
            ->pluck('id');

        Role::query()
            ->where('scope', RoleScope::Project->value)
            ->each(function (Role $role) use ($projectViewPermissionIds): void {
                $role->permissions()->syncWithoutDetaching($projectViewPermissionIds);
            });

        $managerPermissionIds = Permission::query()
            ->whereIn('slug', [
                'projects.update',
                'project_structure.create',
                'project_structure.update',
                'project_structure.delete',
                'work_packages.create',
                'work_packages.update',
                'work_packages.delete',
            ])
            ->pluck('id');

        Role::query()
            ->whereIn('slug', ['project-manager', 'construction-manager'])
            ->each(function (Role $role) use ($managerPermissionIds): void {
                $role->permissions()->syncWithoutDetaching($managerPermissionIds);
            });
    }

    private function seedOrganizationStructure(): void
    {
        $departments = [
            'Management' => 'MGT',
            'Project Management' => 'PM',
            'Engineering' => 'ENG',
            'Planning' => 'PLN',
            'Commercial' => 'COM',
            'Procurement' => 'PRC',
            'Document Control' => 'DOC',
            'QA/QC' => 'QAQC',
            'HSE' => 'HSE',
            'Site Operations' => 'SITE',
        ];

        foreach ($departments as $name => $code) {
            Department::query()->updateOrCreate(
                ['code' => $code],
                ['name' => $name, 'status' => 'active'],
            );
        }

        $designations = [
            ['Project Manager', 'PM', 'Project Management'],
            ['Construction Manager', 'CM', 'Site Operations'],
            ['Site Engineer', 'SE', 'Site Operations'],
            ['Planning Engineer', 'PE', 'Planning'],
            ['Quantity Surveyor', 'QS', 'Commercial'],
            ['Procurement Engineer', 'PRE', 'Procurement'],
            ['Document Controller', 'DC', 'Document Control'],
            ['QA/QC Engineer', 'QAE', 'QA/QC'],
            ['HSE Engineer', 'HSEE', 'HSE'],
            ['Foreman', 'FM', 'Site Operations'],
        ];

        foreach ($designations as [$name, $code, $department]) {
            Designation::query()->updateOrCreate(
                ['code' => $code],
                [
                    'department_id' => Department::query()->where('name', $department)->value('id'),
                    'name' => $name,
                    'status' => 'active',
                ],
            );
        }
    }
}
