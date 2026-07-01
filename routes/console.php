<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\BackupLog;

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
