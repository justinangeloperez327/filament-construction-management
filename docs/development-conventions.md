# Development Conventions

These conventions define the implementation baseline for the construction-management application.

## Architecture

Use a modular Laravel monolith. Prefer Laravel and Filament conventions over additional architectural layers.

Business code belongs in:

- `app/Models` for Eloquent models and relationships
- `app/Enums` for finite business states
- `app/Actions` for focused write operations
- `app/Services` for orchestration or integrations that do not fit a single action
- `app/Policies` for authorization
- `app/Jobs` for queued work
- `app/Notifications` for user notifications
- `app/Support` for small reusable, non-domain helpers
- `app/Filament` for Filament resources, pages and widgets

Do not introduce repositories around Eloquent or DDD layers unless a concrete problem requires them.

## Models and database

- New business models should extend `App\Models\BaseModel` unless Laravel requires another base class.
- Every model must declare its mass-assignable attributes deliberately.
- Use foreign-key constraints for relational integrity.
- Index fields that are routinely filtered, joined, sorted or used as business references.
- Use soft deletes only when restoring a deleted business record is operationally meaningful.
- Avoid polymorphic relationships unless the same relation genuinely serves multiple domain types.
- Project-owned business records must carry an explicit `project_id` foreign key and be authorization-scoped to that project.
- Financial values must use fixed-precision decimal database columns, never floating-point columns.
- Timestamps are stored through Laravel and displayed in the configured application timezone.

## Filament

Navigation groups, in order:

1. Projects
2. Project Controls
3. Documents
4. Engineering
5. QA/QC
6. HSE
7. Site Operations
8. Procurement
9. Commercial
10. Reports
11. Administration

Resources should provide search, useful filters, clear status badges and only the columns required for routine work. Secondary columns should be toggleable.

Use `App\Support\Filament\TableDefaults` and `FormDefaults` for shared values instead of repeating magic numbers.

## Files

Construction files are private by default.

- The configured document disk is `CONSTRUCTION_DOCUMENT_DISK`.
- Local development uses Laravel's private local disk.
- Production can point the document disk to S3-compatible object storage without changing domain code.
- Store files under `documents/projects/{project-reference}/...`.
- Validate extension, MIME type and file size at upload.
- Never expose private storage paths directly. Downloads must be served through an authorized application action or temporary object-storage URL.

## Dates and money

- Default timezone: `Asia/Dubai`
- Default currency: `AED`
- Date display: `M j, Y`
- Date/time display: `M j, Y g:i A`

Use `App\Support\Formatting` for consistent display formatting.

## Testing

Every business group should include:

- migrations and migration rollback coverage where risk justifies it
- model relationship tests
- authorization tests
- Filament resource/page tests
- calculation/service tests for non-trivial business logic
- regression tests for fixed defects

A group is complete only when tests, Pint, migrations and the frontend build pass in CI.
