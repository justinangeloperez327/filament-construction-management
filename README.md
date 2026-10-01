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

## Initial scope

The application foundation is intentionally small. Construction-management modules will be added incrementally on top of this baseline: projects, clients, contracts, teams, documents, RFIs, submittals, tasks, progress, procurement, cost control, and reporting.
