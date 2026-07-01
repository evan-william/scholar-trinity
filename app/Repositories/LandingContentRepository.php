<?php

namespace App\Repositories;

use App\Models\LandingContact;
use App\Models\LandingFaq;
use App\Models\LandingFee;
use App\Models\LandingRequiredDocument;
use App\Models\LandingSection;
use App\Models\LandingSetting;
use App\Models\LandingTimeline;
use Illuminate\Support\Collection;

class LandingContentRepository
{
    public function payload(): array
    {
        return [
            'settings' => $this->settings(),
            'sections' => LandingSection::query()->where('is_active', true)->orderBy('sort_order')->get()->keyBy('key'),
            'timelines' => LandingTimeline::query()->where('is_active', true)->orderBy('sort_order')->get()->groupBy('round'),
            'fees' => LandingFee::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'documents' => LandingRequiredDocument::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'faqs' => LandingFaq::query()->where('is_active', true)->orderBy('sort_order')->get(),
            'contact' => LandingContact::query()->first(),
        ];
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

    public function settings(): Collection
    {
        return LandingSetting::query()
            ->get()
            ->groupBy('group')
            ->map(fn (Collection $rows) => $rows->mapWithKeys(fn (LandingSetting $setting) => [$setting->key => $setting->value]));
    }
}
