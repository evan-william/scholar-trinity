<?php

namespace App\Console\Commands;

use App\Models\ApExamSubject;
use Database\Seeders\ApExamSubjectSeeder;
use Database\Seeders\ExamSeasonSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncRegistrationCatalog extends Command
{
    protected $signature = 'registration:sync-catalog
        {--force : Allow catalog synchronization in production}';

    protected $description = 'Synchronize the default AP exam season and subject catalog, then report form availability';

    public function handle(): int
    {
        if (app()->isProduction() && ! $this->option('force')) {
            $this->error('Production catalog synchronization requires --force.');

            return self::FAILURE;
        }

        DB::transaction(function (): void {
            app(ExamSeasonSeeder::class)->run();
            app(ApExamSubjectSeeder::class)->run();
        });

        $subjects = ApExamSubject::query()
            ->with('examSeason')
            ->where('is_active', true)
            ->orderBy('category')
            ->orderBy('sort_order')
            ->get();

        $categories = $subjects->pluck('category')->filter()->unique()->sort()->values();
        $selectable = $subjects->filter(fn (ApExamSubject $subject) => $subject->isSelectable());

        $this->table(
            ['Code', 'Subject', 'Category', 'Form availability'],
            $subjects->map(fn (ApExamSubject $subject): array => [
                $subject->code,
                $subject->name,
                $subject->category,
                $subject->selectionBlockReason() ?: 'selectable',
            ])->all()
        );

        $this->info(sprintf(
            'Catalog ready: %d active subjects, %d categories (%s), %d selectable now.',
            $subjects->count(),
            $categories->count(),
            $categories->implode(', '),
            $selectable->count(),
        ));

        if ($subjects->count() < 11 || $categories->count() < 3 || $selectable->isEmpty()) {
            $this->error('The catalog is incomplete or has no selectable subjects. Review the table above.');

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
