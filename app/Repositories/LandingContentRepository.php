<?php

namespace App\Repositories;

use App\Models\LandingContact;
use App\Models\LandingFaq;
use App\Models\LandingFee;
use App\Models\LandingRequiredDocument;
use App\Models\LandingSection;
use App\Models\LandingSetting;
use App\Models\LandingTimeline;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Collection;
use Throwable;

class LandingContentRepository
{
    public function payload(): array
    {
        try {
            return [
                'settings' => $this->settings(true),
                'sections' => $this->localizedCollection(
                    LandingSection::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    ['eyebrow', 'title', 'body', 'items']
                )->keyBy('key'),
                'timelines' => $this->localizedCollection(
                    LandingTimeline::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    ['round', 'month', 'description']
                ),
                'fees' => $this->localizedCollection(
                    LandingFee::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    ['name', 'description']
                ),
                'documents' => $this->localizedCollection(
                    LandingRequiredDocument::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    ['name', 'description']
                ),
                'faqs' => $this->localizedCollection(
                    LandingFaq::query()->where('is_active', true)->orderBy('sort_order')->get(),
                    ['question', 'answer']
                ),
                'contact' => $this->localizedModel(
                    LandingContact::query()->first(),
                    ['organization', 'office_hours', 'address', 'social_links']
                ),
            ];
        } catch (Throwable) {
            return $this->emptyPayload();
        }
    }

    public function adminPayload(): array
    {
        return [
            'settings' => $this->settings(),
            'sections' => LandingSection::query()->orderBy('sort_order')->get()->keyBy('key'),
            'timelines' => LandingTimeline::query()->orderBy('sort_order')->get(),
            'fees' => LandingFee::query()->orderBy('sort_order')->get(),
            'documents' => LandingRequiredDocument::query()->orderBy('sort_order')->get(),
            'faqs' => LandingFaq::query()->orderBy('sort_order')->get(),
            'contact' => LandingContact::query()->first(),
        ];
    }

    public function settings(bool $localized = false): Collection
    {
        $locale = App::getLocale();

        return LandingSetting::query()
            ->get()
            ->groupBy('group')
            ->map(fn (Collection $rows) => $rows->mapWithKeys(function (LandingSetting $setting) use ($localized, $locale): array {
                $value = $setting->value ?? [];

                if ($localized) {
                    $value['text'] = $value[$locale] ?? $value['en'] ?? $value['text'] ?? '';
                }

                return [$setting->key => $value];
            }));
    }

    private function localizedCollection(Collection $models, array $fields): Collection
    {
        return $models->map(fn (Model $model) => $this->localizedModel($model, $fields));
    }

    private function localizedModel(?Model $model, array $fields): ?Model
    {
        if (! $model) {
            return null;
        }

        $locale = App::getLocale();
        $translations = $model->getAttribute('translations') ?? [];

        foreach ($fields as $field) {
            $localized = data_get($translations, $locale.'.'.$field);

            if (filled($localized) || is_array($localized)) {
                $model->setAttribute($field, $localized);
            }
        }

        return $model;
    }

    private function emptyPayload(): array
    {
        return [
            'settings' => collect(),
            'sections' => collect(),
            'timelines' => collect(),
            'fees' => collect(),
            'documents' => collect(),
            'faqs' => collect(),
            'contact' => null,
        ];
    }
}
