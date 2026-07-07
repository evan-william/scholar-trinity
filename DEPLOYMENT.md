# Trinity Scholar Deployment Notes

Last updated: 2026-07-07

Do not commit real credentials. Put production values only in the server `.env`.

For step-by-step GitHub pull and zip upload options, see `SERVER_UPLOAD_GUIDE.md`.

## Current Server Target

- Domain: `trinity.sophistec.global`
- App port: `3014`
- Stack: Laravel serves the application and built Vue/Vite assets.
- Production frontend mode: run `npm run build`; do not keep a separate Node dev server running for public traffic.

## Required Server Values

Fill these on the server `.env` only:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://trinity.sophistec.global
APP_PORT=3014

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

Payment provider values are pending until ECPay or NewebPay is chosen:

```dotenv
PAYMENT_GATEWAY_PROVIDER=manual
PAYMENT_GATEWAY_MODE=sandbox
PAYMENT_GATEWAY_ENDPOINT=
```

Receipt/e-invoice provider values are pending until a Taiwan e-invoice provider is chosen:

```dotenv
EINVOICE_PROVIDER=manual
EINVOICE_MODE=sandbox
EINVOICE_ENDPOINT=
```

## First Deploy Checklist

1. Clone repository on server.
2. Create `.env` from `.env.example`.
3. Fill `APP_KEY`, DB credentials, mail credentials, and production URL.
4. Install PHP dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

5. Install and build frontend assets:

```bash
npm install
npm run build
```

6. Run database migrations:

```bash
php artisan migrate --force
```

7. Seed only approved production seeders, if needed:

```bash
php artisan db:seed --class=PaymentSettingSeeder --force
```

8. Prepare caches:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

9. Ensure writable directories:

```bash
storage/
bootstrap/cache/
```

10. Start Laravel app behind the web server or process manager on port `3014`.

## File Security Notes

- Passport and payment proof uploads use the `local` disk and must not be exposed through `public/storage`.
- Do not create a public symlink for private document folders.
- Keep `SECURITY_FILE_MAX_KB=10240` aligned with the registration form max upload size.

## Queue And Scheduler

Current queue connection is database. Run a queue worker in production:

```bash
php artisan queue:work --tries=3 --timeout=90
```

If scheduled backups or cleanup commands are added, configure cron:

```bash
* * * * * php /path/to/trinity-scholar/artisan schedule:run >> /dev/null 2>&1
```

## Verification Checklist

- Landing page loads.
- `/student-registration` loads.
- Registration submit creates a registration reference.
- Passport file is not public.
- Admin login works.
- Admin registration detail shows legal name, passport, practice exams, accommodations, payment, and notes.
- CSV/XLSX export downloads.
- Manual payment proof upload works.
- Receipt form works for service fee receipt.
- HTTPS works on `trinity.sophistec.global`.
