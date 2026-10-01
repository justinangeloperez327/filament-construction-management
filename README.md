# Filament Construction Management

Construction project management application built with **Laravel 13** and **Filament 5**.

## Stack

- PHP 8.3+
- Laravel 13
- Filament 5
- Livewire
- Tailwind CSS 4
- Vite 8
- SQLite for local development
- MySQL / PostgreSQL compatible application conventions

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed
npm install
npm run build
php artisan make:filament-user
php artisan app:grant-system-admin user@example.com
composer dev
```

Open the Filament panel at:

```text
http://localhost:8000/admin
```

## Application defaults

- Timezone: `Asia/Dubai`
- Currency: `AED`
- Private construction documents by default
- Light and dark Filament themes
- Database notifications
- Collapsible project-oriented navigation
- Native Laravel roles and permissions
- Global and project-scoped role separation
- Project-level access isolation
- Project hierarchy: areas, assets, levels and locations
- Discipline and trade master data
- Project work packages with contractor assignments
- Central project records with team membership, stakeholders and project contacts
- User teams, departments, designations and optional company association
- Central company directory with multi-classification support
- Company contacts, addresses and compliance documents
- Login activity tracking
- Shared status, priority, approval, document and project enums
- Shared formatting and file-path conventions

Documentation:

- [Development conventions](docs/development-conventions.md)
- [Access control](docs/access-control.md)
- [Company directory](docs/company-directory.md)
- [Projects](docs/projects.md)
- [Project structure](docs/project-structure.md)

## Development roadmap

The application is being delivered in implementation groups. Groups 1-5 establish the application foundation, access control, company directory, projects and project breakdown structure. Subsequent groups add contracts, documents, engineering workflows, QA/QC, HSE, procurement, commercial controls, dashboards and reporting.
