# Editor Note

This repository is the current base for the Scholar Trinity / Trinity Scholar AP exam registration platform. It was prepared from the existing Laravel project reference and still needs more work before it should be treated as production-ready.

## Current Status

- Backend base exists in Laravel.
- Public pages and admin pages currently use Blade templates.
- Some UI pages still need major redesign work, especially the form, dashboard, payment pages, receipt pages, and admin tables.
- Payment gateway and e-invoice/fapiao flows are still sandbox or adapter-ready, not production provider integration.
- The planned direction is Laravel for the backend with Vue for a cleaner frontend/admin experience.

## Main Work Still Needed

- Replace or rebuild rough Blade UI with a proper frontend structure.
- Decide which pages stay Blade and which pages move to Vue.
- Build a consistent layout system for dashboard, forms, tables, and detail pages.
- Review all registration fields against the final client requirement.
- Replace sandbox payment gateway behavior with real provider integration when provider credentials are available.
- Replace sandbox e-invoice behavior with real fapiao/e-invoice provider integration when confirmed.
- Recheck validation, auth, uploads, export, and audit flow before deployment.
- Update branding, copywriting, and Traditional Chinese text before client demo.

## Local Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite for simple local setup, or MySQL/PostgreSQL if preferred

## First-Time Setup

Run these commands from the project root:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

For SQLite local development, create the database file:

```powershell
New-Item -ItemType File -Path database/database.sqlite -Force
```

Then set this in `.env`:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Build frontend assets once:

```bash
npm run build
```

## Running The App

For normal local development, use two terminals.

Terminal 1:

```bash
php artisan serve
```

Terminal 2:

```bash
npm run dev
```

Open:

```text
http://127.0.0.1:8000
```

## Useful Routes

```text
/                         Landing page
/student-registration     Student AP registration form
/admin/login              Admin login
/admin/dashboard          Admin dashboard
/admin/student-registrations
/admin/payments
/admin/receipts
/admin/exam-seasons
```

## Local Admin Account

The seeder currently creates this local admin account:

```text
Email: test@example.com
Password: StrongPass!123
```

Change this before any real deployment.

## Testing

Run the Laravel test suite:

```bash
php artisan test
```

Run PHP formatting:

```bash
./vendor/bin/pint
```

## Git Notes

- Do not commit `.env`, local databases, uploaded passport files, payment proofs, exports, cache files, or private storage content.
- `.agents/` is ignored and should not be committed.
- Push is handled manually by the repo owner.
