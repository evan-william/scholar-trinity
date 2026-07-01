<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLandingContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings.seo.meta_title' => ['required', 'string', 'max:70'],
            'settings.seo.meta_description' => ['required', 'string', 'max:170'],
            'settings.seo.keywords' => ['nullable', 'string', 'max:255'],
            'settings.seo.canonical_url' => ['nullable', 'url', 'max:255'],
            'settings.hero.platform_name' => ['required', 'string', 'max:120'],
            'settings.hero.title' => ['required', 'string', 'max:120'],
            'settings.hero.introduction' => ['required', 'string', 'max:300'],
            'settings.hero.primary_button' => ['required', 'string', 'max:40'],
            'settings.hero.secondary_button' => ['required', 'string', 'max:40'],
            'settings.hero.banner_text' => ['nullable', 'string', 'max:120'],
            'sections.*.title' => ['required', 'string', 'max:140'],
            'sections.*.eyebrow' => ['nullable', 'string', 'max:80'],
            'sections.*.body' => ['required', 'string', 'max:3000'],
            'sections.*.items' => ['nullable', 'string', 'max:3000'],
            'sections.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'timelines' => ['required', 'array', 'min:1'],
            'timelines.*.round' => ['required', 'string', 'max:80'],
            'timelines.*.month' => ['required', 'string', 'max:40'],
            'timelines.*.status' => ['required', 'in:Open,Upcoming,Closed'],
            'timelines.*.description' => ['nullable', 'string', 'max:500'],
            'fees' => ['required', 'array', 'min:1'],
            'fees.*.name' => ['required', 'string', 'max:120'],
            'fees.*.description' => ['nullable', 'string', 'max:500'],
            'fees.*.currency' => ['required', 'string', 'max:8'],
            'fees.*.amount' => ['required', 'integer', 'min:0', 'max:999999'],
            'documents' => ['required', 'array', 'min:1'],
            'documents.*.name' => ['required', 'string', 'max:120'],
            'documents.*.description' => ['nullable', 'string', 'max:500'],
            'documents.*.is_required' => ['nullable', 'boolean'],
            'faqs' => ['required', 'array', 'min:1'],
            'faqs.*.question' => ['required', 'string', 'max:180'],
            'faqs.*.answer' => ['required', 'string', 'max:1000'],
            'contact.organization' => ['required', 'string', 'max:160'],
            'contact.email' => ['nullable', 'email', 'max:160'],
            'contact.phone' => ['nullable', 'string', 'max:60'],
            'contact.whatsapp' => ['nullable', 'string', 'max:80'],
            'contact.office_hours' => ['nullable', 'string', 'max:160'],
            'contact.address' => ['nullable', 'string', 'max:500'],
            'contact.map_url' => ['nullable', 'url', 'max:255'],
            'contact.social_links' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
