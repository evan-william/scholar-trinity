# Security and Data Protection

## Hosting Checklist

- Use HTTPS with TLS 1.2 or newer.
- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Set `SECURITY_FORCE_HTTPS=true` behind a correctly configured HTTPS proxy.
- Use a strong `APP_KEY`.
- Set secure session options in production: `SESSION_SECURE_COOKIE=true`, `SESSION_HTTP_ONLY=true`, and `SESSION_SAME_SITE=lax`.
- Restrict public firewall access to HTTP/HTTPS only.
- Restrict SSH by IP/VPN and disable password login where possible.
- Keep the database off the public internet.
- Run dependency and OS updates regularly.
- Keep `storage/` and `.env` outside public web access.

## File Security

Passport and payment proof files are stored on the private Laravel `local` disk under `storage/app/private`. Files are previewed/downloaded only through authenticated admin controllers.

Uploads are validated for:

- PDF/JPG/JPEG/PNG only
- MIME type allow-list
- Configurable max size, default `SECURITY_FILE_MAX_KB=5120`
- Suspicious executable extensions
- Double extensions
- Path traversal characters

## Audit Log

The `security_audit_logs` table records high-level platform events across auth, registration, payment, documents, receipts, and exports. Sensitive keys are masked before storage.

Admin audit UI:

```text
/admin/security/audit
```

## Backup

Local SQLite backup command:

```bash
php artisan security:backup-database
```

Backups are written to the private local disk under `backups/` and tracked in `backup_logs`. Production deployments should encrypt and copy backups to restricted external storage.

## Production Environment

Recommended `.env` values:

```dotenv
APP_ENV=production
APP_DEBUG=false
SECURITY_FORCE_HTTPS=true
SECURITY_FILE_MAX_KB=5120
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```
