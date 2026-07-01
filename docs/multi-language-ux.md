# Multi-language and UX

## Locale System

Supported public locales:

- `en`
- `zh-TW`

The public route `/locale/{locale}` validates the requested locale, stores it in session and cookie, then redirects back to the current page. `ApplyLocale` middleware applies the locale to every web request and falls back to English.

Traditional Chinese Laravel files use the framework directory name `zh_TW`, while URLs and cookies use `zh-TW`.

## Language Switcher

The reusable anonymous Blade component is:

```blade
<x-language-switcher />
```

It is currently placed on the landing page and the student registration form. The component keeps the user on the current URL and does not clear form inputs because switching is a GET redirect that does not submit the form.

## Registration UX

The student registration form includes:

- Responsive layout and touch-friendly inputs
- Progress indicator
- Step navigation
- Short section instructions
- Error summary
- Inline error for supported fields
- Preserved old input after validation failure
- Review step before final submission
- Server-side `confirmed_review` validation
- Loading state to prevent double submission

## Email Templates

Email templates now use bilingual translation keys and a reusable HTML email layout:

```blade
<x-emails.layout>
    ...
</x-emails.layout>
```

Registration and payment instruction emails include plain text fallback views. Payment confirmation, passport re-upload, and receipt subjects use localization keys.

The `email_template_settings` table provides a future admin-managed template layer with per-template/per-locale subject, HTML body, and text body fields.

## Security Notes

- Locale is allow-listed.
- Dynamic values in Blade emails are escaped by default.
- Passport file paths are not exposed in document emails.
- Public submissions remain rate-limited.
- Final submission requires server-side confirmation.
