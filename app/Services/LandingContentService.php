<?php

namespace App\Services;

use App\Models\LandingContact;
use App\Models\LandingFaq;
use App\Models\LandingFee;
use App\Models\LandingRequiredDocument;
use App\Models\LandingSection;
use App\Models\LandingSetting;
use App\Models\LandingTimeline;
use App\Repositories\LandingContentRepository;
use Illuminate\Support\Facades\DB;

class LandingContentService
{
    public function __construct(private readonly LandingContentRepository $repository)
    {
    }

    public function publicPayload(): array
    {
        return $this->repository->payload() + [
            'registrationSettings' => app(PublicRegistrationSettings::class)->all(),
        ];
    }

    public function adminPayload(): array
    {
        return $this->repository->adminPayload();
    }

    public function update(array $data): void
    {
        DB::transaction(function () use ($data): void {
            foreach (($data['settings'] ?? []) as $group => $settings) {
                foreach ($settings as $key => $value) {
                    LandingSetting::query()->updateOrCreate(
                        ['group' => $group, 'key' => $key],
                        ['value' => is_array($value) ? $value : ['text' => $value]]
                    );
                }
            }

            foreach (($data['sections'] ?? []) as $key => $section) {
                LandingSection::query()->updateOrCreate(
                    ['key' => $key],
                    [
                        'eyebrow' => $section['eyebrow'] ?? null,
                        'title' => $section['title'],
                        'body' => $section['body'],
                        'items' => $this->linesToItems($section['items'] ?? ''),
                        'is_active' => true,
                        'sort_order' => (int) ($section['sort_order'] ?? 0),
                    ]
                );
            }

            $this->replaceRows(LandingTimeline::class, $data['timelines'] ?? [], fn ($row, $index) => [
                'round' => $row['round'],
                'month' => $row['month'],
                'status' => $row['status'],
                'description' => $row['description'] ?? null,
                'sort_order' => $index,
                'is_active' => true,
            ]);

            $this->replaceRows(LandingFee::class, $data['fees'] ?? [], fn ($row, $index) => [
                'name' => $row['name'],
                'description' => $row['description'] ?? null,
                'currency' => $row['currency'] ?: 'NTD',
                'amount' => (int) ($row['amount'] ?? 0),
                'sort_order' => $index,
                'is_active' => true,
            ]);

            $this->replaceRows(LandingRequiredDocument::class, $data['documents'] ?? [], fn ($row, $index) => [
                'name' => $row['name'],
                'description' => $row['description'] ?? null,
                'is_required' => filter_var($row['is_required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $index,
                'is_active' => true,
            ]);

            $this->replaceRows(LandingFaq::class, $data['faqs'] ?? [], fn ($row, $index) => [
                'question' => $row['question'],
                'answer' => $row['answer'],
                'sort_order' => $index,
                'is_active' => true,
            ]);

            LandingContact::query()->updateOrCreate(
                ['id' => LandingContact::query()->value('id')],
                [
                    'organization' => $data['contact']['organization'],
                    'email' => $data['contact']['email'] ?? null,
                    'phone' => $data['contact']['phone'] ?? null,
                    'whatsapp' => $data['contact']['whatsapp'] ?? null,
                    'office_hours' => $data['contact']['office_hours'] ?? null,
                    'address' => $data['contact']['address'] ?? null,
                    'map_url' => $data['contact']['map_url'] ?? null,
                    'social_links' => $this->linesToItems($data['contact']['social_links'] ?? ''),
                ]
            );
        });
    }

    private function replaceRows(string $model, array $rows, callable $mapper): void
    {
        $model::query()->delete();

        foreach (array_values($rows) as $index => $row) {
            if ($this->rowIsBlank($row)) {
                continue;
            }

            $model::query()->create($mapper($row, $index));
        }
    }

    private function rowIsBlank(array $row): bool
    {
        return collect($row)->filter(fn ($value) => filled($value))->isEmpty();
    }

    private function linesToItems(string|array|null $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
