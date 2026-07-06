<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\BackupLog;
use App\Models\RegistrationPayment;
use App\Services\PaymentFlowService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('security:backup-database', function (): int {
    $log = BackupLog::query()->create([
        'backup_type' => 'database',
        'status' => 'running',
        'disk' => 'local',
        'started_at' => now(),
    ]);

    try {
        $connection = config('database.default');
        $database = config("database.connections.{$connection}.database");
        abort_unless($connection === 'sqlite' && $database && File::exists($database), 422, 'Only local SQLite backup is supported by this built-in command.');

        $path = 'backups/database-'.now()->format('Ymd-His').'.sqlite';
        Storage::disk('local')->put($path, File::get($database));
        $size = Storage::disk('local')->size($path);

        $log->update([
            'status' => 'completed',
            'path' => $path,
            'size_bytes' => $size,
            'completed_at' => now(),
            'message' => 'Database backup completed.',
        ]);
        $this->info('Backup created: '.$path);

        return self::SUCCESS;
    } catch (\Throwable $exception) {
        $log->update([
            'status' => 'failed',
            'message' => $exception->getMessage(),
            'completed_at' => now(),
        ]);
        $this->error($exception->getMessage());

        return self::FAILURE;
    }
})->purpose('Create a private database backup and log the result');

Artisan::command('security:backup-storage {--zip : Create a zip archive when ZipArchive is available}', function (): int {
    $log = BackupLog::query()->create([
        'backup_type' => $this->option('zip') ? 'storage_zip' : 'storage_manifest',
        'status' => 'running',
        'disk' => 'local',
        'started_at' => now(),
    ]);

    try {
        $disk = Storage::disk('local');
        $timestamp = now()->format('Ymd-His');
        $files = collect($disk->allFiles())
            ->reject(fn (string $path) => str_starts_with($path, 'backups/'))
            ->values();

        $manifest = $files->map(fn (string $path) => [
            'path' => $path,
            'size_bytes' => $disk->size($path),
            'last_modified' => date('c', $disk->lastModified($path)),
        ])->all();

        $manifestPath = 'backups/storage-manifest-'.$timestamp.'.json';
        $disk->put($manifestPath, json_encode([
            'created_at' => now()->toIso8601String(),
            'disk' => 'local',
            'file_count' => count($manifest),
            'files' => $manifest,
        ], JSON_PRETTY_PRINT));

        $path = $manifestPath;
        $size = $disk->size($manifestPath);

        if ($this->option('zip')) {
            abort_unless(class_exists(\ZipArchive::class), 422, 'ZipArchive extension is not available.');

            $zipPath = 'backups/storage-'.$timestamp.'.zip';
            $absoluteZipPath = $disk->path($zipPath);
            File::ensureDirectoryExists(dirname($absoluteZipPath));

            $zip = new \ZipArchive();
            abort_unless($zip->open($absoluteZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true, 500, 'Unable to create storage zip.');

            foreach ($files as $file) {
                $zip->addFile($disk->path($file), $file);
            }
            $zip->addFile($disk->path($manifestPath), $manifestPath);
            $zip->close();

            $path = $zipPath;
            $size = $disk->size($zipPath);
        }

        $log->update([
            'status' => 'completed',
            'path' => $path,
            'size_bytes' => $size,
            'completed_at' => now(),
            'message' => 'Private storage backup metadata completed for '.$files->count().' files.',
        ]);
        $this->info('Storage backup created: '.$path);

        return self::SUCCESS;
    } catch (\Throwable $exception) {
        $log->update([
            'status' => 'failed',
            'message' => $exception->getMessage(),
            'completed_at' => now(),
        ]);
        $this->error($exception->getMessage());

        return self::FAILURE;
    }
})->purpose('Create a private storage backup manifest or optional zip and log the result');

Artisan::command('payments:send-reminders {--days=2 : Only remind payments due within this many days} {--limit=50 : Maximum reminders to send}', function (): int {
    $service = app(PaymentFlowService::class);
    $days = max((int) $this->option('days'), 0);
    $limit = max((int) $this->option('limit'), 1);

    $payments = RegistrationPayment::query()
        ->with(['registration.contact', 'registration.exams'])
        ->whereIn('payment_status', ['pending', 'proof_uploaded', 'waiting_verification'])
        ->where(function ($query) use ($days): void {
            $query->whereNull('payment_deadline_at')
                ->orWhere('payment_deadline_at', '<=', now()->addDays($days));
        })
        ->latest('payment_deadline_at')
        ->limit($limit)
        ->get();

    foreach ($payments as $payment) {
        $service->sendReminder($payment);
        $this->line('Reminder sent: '.$payment->payment_reference);
    }

    $this->info('Payment reminders sent: '.$payments->count());

    return self::SUCCESS;
})->purpose('Send payment reminder emails for pending AP registration payments');
