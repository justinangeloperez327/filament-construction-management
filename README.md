# Filament Construction Management

Construction project management application built with **Laravel 13** and **Filament 5**.

## Stack

- PHP 8.3+
- Laravel 13
- Filament 5
- Livewire
- Tailwind CSS 4
- Vite
- SQLite for local development
- MySQL / PostgreSQL compatible application conventions

## Local setup

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
npm install
npm run build
php artisan make:filament-user
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
- Shared status, priority, approval, document and project enums
- Shared formatting and file-path conventions

Development conventions are documented in [docs/development-conventions.md](docs/development-conventions.md).

## Development roadmap

The application is being delivered in implementation groups. Group 1 establishes the application foundation. Subsequent groups add users and permissions, companies, projects, documents, engineering workflows, QA/QC, HSE, procurement, commercial controls, dashboards and reporting.
