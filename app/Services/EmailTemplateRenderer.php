<?php

namespace App\Services;

use App\Models\EmailTemplateSetting;
use Throwable;

class EmailTemplateRenderer
{
    public function render(string $key, string $locale, array $variables): ?array
    {
        try {
            $template = EmailTemplateSetting::query()
                ->where('template_key', $key)
                ->where('locale', $locale)
                ->where('is_active', true)
                ->first();
        } catch (Throwable) {
            return null;
        }

        if (! $template) {
            return null;
        }

        $replacements = collect($variables)->mapWithKeys(
            fn ($value, string $name) => ['{{ '.$name.' }}' => e((string) $value), '{{'.$name.'}}' => e((string) $value)]
        )->all();

        return [
            'subject' => html_entity_decode(strtr($template->subject, $replacements)),
            'html' => strtr($template->body_html, $replacements),
            'text' => html_entity_decode(strip_tags(strtr($template->body_text ?: $template->body_html, $replacements))),
        ];
    }
}
