<?php

namespace App\Services;

use App\Models\LandingContact;
use App\Models\LandingFaq;
use App\Models\LandingFee;
use App\Models\LandingRequiredDocument;
use App\Models\LandingSection;
use App\Models\LandingSetting;
use App\Models\LandingTimeline;
use App\Models\SystemSetting;
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
        return $this->repository->adminPayload() + [
            'registrationSettings' => app(PublicRegistrationSettings::class)->all(),
        ];
    }

    public function update(array $data): void
    {
        DB::transaction(function () use ($data): void {
            foreach (($data['settings'] ?? []) as $group => $settings) {
                foreach ($settings as $key => $value) {
                    $localized = $this->localizedInput($value);
                    LandingSetting::query()->updateOrCreate(
                        ['group' => $group, 'key' => $key],
                        ['value' => $localized + ['text' => $localized['en']]]
                    );
                }
            }

            foreach (($data['registration_settings'] ?? []) as $key => $value) {
                $localized = $this->localizedInput($value);
                $primaryValue = str_ends_with($key, '_zh') ? $localized['zh_TW'] : $localized['en'];

                SystemSetting::query()->updateOrCreate(
                    ['key' => 'registration.'.$key],
                    [
                        'group' => 'registration',
                        'value' => $primaryValue,
                        'type' => 'string',
                        'description' => 'Public registration content managed from Landing Content.',
                        'is_public' => true,
                    ]
                );

                if ($key !== 'test_site_map_url' && ! str_ends_with($key, '_en') && ! str_ends_with($key, '_zh')) {
                    SystemSetting::query()->updateOrCreate(
                        ['key' => 'registration.'.$key.'_zh'],
                        [
                            'group' => 'registration',
                            'value' => $localized['zh_TW'],
                            'type' => 'string',
                            'description' => 'Traditional Chinese public registration content managed from Landing Content.',
                            'is_public' => true,
                        ]
                    );
                }
            }

            foreach (($data['sections'] ?? []) as $key => $section) {
                $localized = $this->localizedFields($section, ['eyebrow', 'title', 'body', 'items'], ['items']);
                LandingSection::query()->updateOrCreate(
                    ['key' => $key],
                    [
                        'eyebrow' => $localized['en']['eyebrow'] ?? null,
                        'title' => $localized['en']['title'],
                        'body' => $localized['en']['body'],
                        'items' => $localized['en']['items'],
                        'translations' => $localized,
                        'is_active' => true,
                        'sort_order' => (int) ($section['sort_order'] ?? 0),
                    ]
                );
            }

            $this->replaceRows(LandingTimeline::class, $data['timelines'] ?? [], function ($row, $index): array {
                $localized = $this->localizedFields($row, ['round', 'month', 'description']);

                return [
                    'round' => $localized['en']['round'],
                    'month' => $localized['en']['month'],
                    'status' => $row['status'],
                    'description' => $localized['en']['description'] ?? null,
                    'translations' => $localized,
                    'sort_order' => $index,
                    'is_active' => true,
                ];
            });

            $this->replaceRows(LandingFee::class, $data['fees'] ?? [], function ($row, $index): array {
                $localized = $this->localizedFields($row, ['name', 'description']);

                return [
                    'name' => $localized['en']['name'],
                    'description' => $localized['en']['description'] ?? null,
                    'translations' => $localized,
                    'currency' => $row['currency'] ?: 'NTD',
                    'amount' => (int) ($row['amount'] ?? 0),
                    'sort_order' => $index,
                    'is_active' => true,
                ];
            });

            $this->replaceRows(LandingRequiredDocument::class, $data['documents'] ?? [], function ($row, $index): array {
                $localized = $this->localizedFields($row, ['name', 'description']);

                return [
                    'name' => $localized['en']['name'],
                    'description' => $localized['en']['description'] ?? null,
                    'translations' => $localized,
                    'is_required' => filter_var($row['is_required'] ?? false, FILTER_VALIDATE_BOOLEAN),
                    'sort_order' => $index,
                    'is_active' => true,
                ];
            });

            $this->replaceRows(LandingFaq::class, $data['faqs'] ?? [], function ($row, $index): array {
                $localized = $this->localizedFields($row, ['question', 'answer']);

                return [
                    'question' => $localized['en']['question'],
                    'answer' => $localized['en']['answer'],
                    'translations' => $localized,
                    'sort_order' => $index,
                    'is_active' => true,
                ];
            });

            $localizedContact = $this->localizedFields(
                $data['contact'],
                ['organization', 'office_hours', 'address', 'social_links'],
                ['social_links']
            );

            LandingContact::query()->updateOrCreate(
                ['id' => LandingContact::query()->value('id')],
                [
                    'organization' => $localizedContact['en']['organization'],
                    'email' => $data['contact']['email'] ?? null,
                    'phone' => $data['contact']['phone'] ?? null,
                    'whatsapp' => $data['contact']['whatsapp'] ?? null,
                    'office_hours' => $localizedContact['en']['office_hours'] ?? null,
                    'address' => $localizedContact['en']['address'] ?? null,
                    'map_url' => $data['contact']['map_url'] ?? null,
                    'social_links' => $localizedContact['en']['social_links'],
                    'translations' => $localizedContact,
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

    private function localizedInput(mixed $value): array
    {
        if (! is_array($value)) {
            $text = trim((string) $value);

            return ['en' => $text, 'zh_TW' => $text];
        }

        $english = trim((string) ($value['en'] ?? $value['text'] ?? ''));
        $chinese = trim((string) ($value['zh_TW'] ?? $english));

        return ['en' => $english, 'zh_TW' => $chinese !== '' ? $chinese : $english];
    }

    private function localizedFields(array $row, array $fields, array $listFields = []): array
    {
        $localized = ['en' => [], 'zh_TW' => []];

        foreach ($fields as $field) {
            $value = $this->localizedInput($row[$field] ?? null);

            foreach (['en', 'zh_TW'] as $locale) {
                $localized[$locale][$field] = in_array($field, $listFields, true)
                    ? $this->linesToItems($value[$locale])
                    : $value[$locale];
            }
        }

        return $localized;
    }
}
