# Trinity Scholar Server Checklist

Use this as the deployment QA checklist. Do not commit real passwords or provider credentials.

## Before Deploy

- Confirm final branch and commit hash with Evan/Ricky.
- Confirm server domain points to `72.60.210.71`.
- Confirm HTTPS is active for `trinity.sophistec.global`.
- Create production `.env` from `.env.production.example`.
- Fill MySQL database name, username, and password on the server only.
- Fill SMTP credentials on the server only.
- Keep passport and payment proof files on the private `local` disk.
- Confirm `storage/app` is not exposed by web server aliases.

## Laravel Runtime

- PHP version matches `composer.json`.
- Required extensions installed: `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`, `zip`.
- Composer dependencies installed with `--no-dev`.
- Node build generated with `npm run build`.
- `APP_KEY` generated.
- Database migrations run.
- `storage` and `bootstrap/cache` are writable by the site user.
- Queue worker is running if `QUEUE_CONNECTION` is not `sync`.
- Scheduler is configured with Laravel `schedule:run`.

## Functional QA

- Landing page opens.
- Student registration creates a reference number.
- Required fields reject blank/fake data.
- Passport upload stores privately.
- Payment instruction email sends.
- Manual proof upload works.
- Admin can verify payment.
- Admin can verify registration and completion email sends.
- Receipt request can be created and manually issued.
- Admin export downloads and masks passport by default.
- Audit log records document/payment/receipt/admin actions.

## Backup

- Daily database backup configured.
- Private uploaded files backup configured.
- Run `php artisan security:backup-storage` to create a private storage manifest.
- Run `php artisan security:backup-storage --zip` only if the server has `ZipArchive` and enough disk space.
- Restore process tested on a non-production location.
- Backup storage access limited to the ops owner.
