# Trinity Scholar Deploy Commands

Run these on the server from the project directory after pulling the intended commit.

```bash
cp .env.production.example .env
php artisan key:generate
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

For a normal update after `.env` already exists:

```bash
git pull
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

Scheduler cron:

```bash
* * * * * cd /path/to/trinity-scholar && php artisan schedule:run >> /dev/null 2>&1
```

Queue worker example:

```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```

Manual backup checks:

```bash
php artisan security:backup-database
php artisan security:backup-storage
```

For MySQL/MariaDB production, configure a server-side `mysqldump` job using credentials from the server `.env` or hosting secret manager. Do not commit the dump command with real passwords.

Do not paste real DB, SMTP, ECPay, NewebPay, or e-invoice secrets into this file.
