# Trinity Scholar Server Upload Guide

This guide is for deploying to `trinity.sophistec.global` without committing secrets.

Do not commit SSH passwords, DB passwords, SMTP passwords, ECPay keys, NewebPay keys, or e-invoice keys.

## Recommended Flow: GitHub Pull On Server

Use this when the server can access GitHub.

From local machine:

```bash
git add PROGRESS.md SERVER_UPLOAD_GUIDE.md resources/views/landing/index.blade.php resources/views/student-registration/create.blade.php
git commit -m "Refine AP registration content and deployment guide"
git push origin main
```

On the server:

```bash
ssh sophistec-trinity@72.60.210.71
cd /path/to/scholar-trinity
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

Why this is recommended:
- Files ignored by Git, such as `vendor/`, `node_modules/`, `.env`, logs, and local storage, should not be uploaded from local.
- The server should reinstall Composer and npm packages from `composer.lock` and `package-lock.json`.
- The server `.env` stays private.

## Fallback Flow: Zip Upload From Terminal

Use this only when GitHub pull is unavailable.

Create a deploy zip that excludes local-only folders:

```powershell
Compress-Archive -Path app,bootstrap,config,database,docs,lang,public,resources,routes,tests,artisan,composer.json,composer.lock,package.json,package-lock.json,phpunit.xml,vite.config.js,README.md,LICENSE,.env.production.example -DestinationPath scholar-trinity-deploy.zip -Force
```

Upload the zip:

```powershell
scp .\scholar-trinity-deploy.zip sophistec-trinity@72.60.210.71:/home/sophistec-trinity/
```

On the server:

```bash
ssh sophistec-trinity@72.60.210.71
cd /home/sophistec-trinity
unzip -o scholar-trinity-deploy.zip -d scholar-trinity
cd scholar-trinity
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## Database Setup

The DB values are not created by Git. They must be created on the server or hosting panel.

Create or ask the server owner to create:
- database name
- database username
- database password

Then put them only in the server `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

After `.env` is filled:

```bash
php artisan key:generate
php artisan migrate --force
```

## Port 3014

The application must be served by the server process manager or web server on port `3014`.

Laravel/Vite production mode:
- Run `npm run build`.
- Do not run `npm run dev` for production.
- Do not upload local `node_modules`.

## Quick Smoke Test After Deploy

Open:
- `https://trinity.sophistec.global`
- `https://trinity.sophistec.global/student-registration`
- admin login URL configured by the app

Check:
- landing content is text-based, not a pasted poster image
- registration form opens without student login
- passport upload stores privately
- admin can view submitted registrations
- manual payment instruction page loads
