# Access Control

Group 2 introduces native Laravel role and permission management.

## Global and project roles

Roles have a scope:

- `global`: attached directly to a user and valid across the application.
- `project`: reserved for project membership and will be attached through the project membership model introduced with Projects.

This prevents project roles such as Project Manager or Site Engineer from accidentally granting organization-wide access.

## System Administrator

The `System Administrator` role is global and receives a Laravel Gate bypass. Assign it only to trusted administrators.

After creating a Filament user, grant the role with:

```bash
php artisan db:seed
php artisan app:grant-system-admin user@example.com
```

## Permissions

Permissions use the format:

```text
resource.action
```

Examples:

```text
users.view_any
projects.update
documents.approve
reports.export
```

System permissions are seeded from `App\Support\Authorization\PermissionRegistry` and protected from ordinary update/delete operations. Custom permissions may be created through Filament.

## Authorization rules

- Filament resources rely on Laravel policies.
- System Administrator bypasses ordinary policy checks.
- Users without a required permission receive HTTP 403.
- Users cannot delete their own account.
- Inactive users cannot access the Filament panel.
- Successful logins update `last_login_at` and create a login activity entry.

## Project access

Project-scoped roles exist now, but direct user/project assignments intentionally wait until the Project model is introduced. Group 4 will create the project membership pivot and enforce project-level isolation using these roles.
