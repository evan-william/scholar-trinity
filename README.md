# Trinity Scholar

Trinity Scholar is a Laravel-based AP exam registration and scholarship operations platform for student registration, exam selection, payment tracking, receipt management, and admin reporting.

## Repository

Recommended GitHub metadata:

| Field | Value |
| --- | --- |
| Repository name | `scholar-trinity` |
| Description | `Trinity Scholar: AP exam registration, payment, and admin management platform` |
| Topics | `laravel php ap-exam registration-platform student-management payment-flow admin-dashboard bilingual receipt-management` |

## Features

| Module | Status | Notes |
| --- | --- | --- |
| Landing page | Available | Program overview, timeline, fees, documents, FAQ, and contact sections |
| Student registration | Available | Student, school, parent, emergency contact, passport, agreements, and exam selection |
| Exam management | Available | AP subjects, exam dates, quota, fees, service fees, and late fees |
| Admin dashboard | Available | Registration counts, payment overview, incomplete records, and subject summaries |
| Registration management | Available | Search, filters, detail view, edits, verification, internal notes, and print view |
| Passport management | Available | Private file storage, preview, download, replace, status update, and re-upload request |
| Export | Available | CSV/XLSX export with standard, TPCA, and school templates |
| Payment flow | Available | Manual bank transfer, proof upload, admin verification, payment logs, and gateway-ready sandbox payloads |
| Receipt/fapiao | Available | Service-fee receipt request, admin issue flow, export, and sandbox e-invoice transaction |
| Multi-language UX | Available | English and Traditional Chinese language files with a language switcher |
| Security and audit | Available | Admin middleware, session timeout, private uploads, validation, audit logs, and backup log models |
| Annual reuse | Available | Exam seasons, duplicate season setup, archiving, yearly reports, and subject quotas |

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2+, Laravel 12 |
| Frontend | Blade, Vite, Tailwind CSS 4 |
| Database | SQLite by default, configurable through Laravel database settings |
| Testing | PHPUnit / Laravel Feature Tests |
| Email | Laravel Mailables |
| Files | Laravel local private storage |

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite for local development, or another Laravel-supported database

## Quick Start

```bash
git clone https://github.com/evan-william/scholar-trinity.git
cd scholar-trinity

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed

npm run build
php artisan serve
```

Open the local app at:

```text
http://127.0.0.1:8000
```

## Development

Run the Laravel dev stack:

```bash
composer run dev
```

Run tests:

```bash
composer test
```

Format PHP code:

```bash
./vendor/bin/pint
```

## Main Routes

| Area | Route |
| --- | --- |
| Landing page | `/` |
| AP registration form | `/student-registration` |
| Payment page | `/payments/{registrationNumber}` |
| Admin login | `/admin/login` |
| Admin dashboard | `/admin/dashboard` |
| Registration management | `/admin/student-registrations` |
| Payment management | `/admin/payments` |
| Receipt management | `/admin/receipts` |
| Exam season management | `/admin/exam-seasons` |

## Security Notes

- Do not commit `.env`, local SQLite databases, uploaded passport files, payment proofs, exports, logs, or cached views.
- Passport and payment proof uploads are stored on the local private disk, not in public web storage.
- Admin routes are protected by authentication, admin middleware, and session timeout middleware.
- File uploads validate size, extension, MIME type, suspicious file names, and path traversal patterns.
- Payment gateway and e-invoice flows currently include sandbox/adapter-ready behavior; production provider credentials and callbacks must be reviewed before going live.

## Project Structure

```text
scholar-trinity/
|-- app/
|   |-- Http/Controllers/
|   |-- Http/Requests/
|   |-- Mail/
|   |-- Models/
|   |-- Repositories/
|   `-- Services/
|-- config/
|-- database/
|   |-- migrations/
|   `-- seeders/
|-- docs/
|-- lang/
|   |-- en/
|   `-- zh_TW/
|-- resources/
|   `-- views/
|-- routes/
|-- storage/
|-- tests/
`-- README.md
```

## Documentation

Additional notes are available in:

- `docs/payment-flow.md`
- `docs/receipt-fapiao-management.md`
- `docs/multi-language-ux.md`
- `docs/security-data-protection.md`
- `docs/annual-future-use.md`
- `docs/AP_Exam_Registration_Mockup.html`

## License

This project is licensed under the MIT License. See `LICENSE` for details.

## Authors

- Evan William
  - GitHub: [@evan-william](https://github.com/evan-william)

- BattedBrake
  - GitHub: [@BattedBrake](https://github.com/BattedBrake)
