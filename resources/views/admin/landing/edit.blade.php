@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $copySettings = $settings->get('copy', collect());
    $cmsSections = [
        'registration_intro' => ['label' => 'Registration Form Intro', 'model' => $sections->get('registration_intro')],
        'registration_notice' => ['label' => 'Registration Form Important Notice', 'model' => $sections->get('registration_notice')],
        'overview' => ['label' => 'Program Overview', 'model' => $sections->get('overview')],
        'process' => ['label' => 'Registration Process', 'model' => $sections->get('process')],
        'privacy' => ['label' => 'Privacy', 'model' => $sections->get('privacy')],
    ];
    $settingText = function ($group, string $key, string $locale): string {
        $value = data_get($group, $key, []);
        return (string) ($value[$locale] ?? $value['en'] ?? $value['text'] ?? '');
    };
    $modelText = function ($model, string $field, string $locale): string {
        $fallback = $model?->{$field} ?? '';
        $value = data_get($model?->translations, $locale.'.'.$field, $fallback);
        return is_array($value) ? implode("\n", $value) : (string) $value;
    };
    $registrationText = function (string $key, string $locale) use ($registrationSettings): string {
        if ($key === 'test_site_map_url') {
            return (string) ($registrationSettings[$key] ?? '');
        }
        return (string) ($registrationSettings[$key.($locale === 'zh_TW' ? '_zh' : '')] ?? $registrationSettings[$key] ?? '');
    };
@endphp
<x-admin-shell
    :title="__('landing.admin_title')"
    subtitle="Edit the English and Traditional Chinese website copy. Layout and styling remain protected."
>
    <form method="POST" action="{{ route('admin.landing.update') }}">
        @csrf
        @method('PUT')

        <section class="card">
            <div class="section-title"><div><h2>SEO Metadata</h2><p>Search metadata is stored separately for each language.</p></div></div>
            @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                <div class="row-card">
                    <h3>{{ $language }}</h3>
                    <div class="grid">
                        <label>Meta Title<input name="settings[seo][meta_title][{{ $locale }}]" value="{{ old("settings.seo.meta_title.$locale", $settingText($seo, 'meta_title', $locale)) }}" maxlength="70" required></label>
                        <label>Keywords<input name="settings[seo][keywords][{{ $locale }}]" value="{{ old("settings.seo.keywords.$locale", $settingText($seo, 'keywords', $locale)) }}" maxlength="255"></label>
                        <label>Canonical URL<input name="settings[seo][canonical_url][{{ $locale }}]" value="{{ old("settings.seo.canonical_url.$locale", $settingText($seo, 'canonical_url', $locale)) }}" type="url"></label>
                        <label>Meta Description<textarea name="settings[seo][meta_description][{{ $locale }}]" maxlength="170" required>{{ old("settings.seo.meta_description.$locale", $settingText($seo, 'meta_description', $locale)) }}</textarea></label>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><div><h2>All Landing Page Text</h2><p>Edit the remaining English and Traditional Chinese public copy. These fields change text only; the approved layout stays locked.</p></div></div>
            @foreach (config('landing_copy', []) as $copyGroup)
                <div class="row-card">
                    <h3>{{ $copyGroup['label'] }}</h3>
                    <div class="grid">
                        @foreach ($copyGroup['fields'] as $key => $field)
                            @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                                @if ($field['multiline'] ?? false)
                                    <label>{{ $field['label'] }} ({{ $language }})<textarea name="settings[copy][{{ $key }}][{{ $locale }}]" required>{{ old("settings.copy.$key.$locale", $settingText($copySettings, $key, $locale) ?: $field[$locale]) }}</textarea></label>
                                @else
                                    <label>{{ $field['label'] }} ({{ $language }})<input name="settings[copy][{{ $key }}][{{ $locale }}]" value="{{ old("settings.copy.$key.$locale", $settingText($copySettings, $key, $locale) ?: $field[$locale]) }}" required></label>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><div><h2>Hero Banner</h2><p>Controls the main landing-page banner copy.</p></div><a class="btn light" href="{{ route('landing') }}" target="_blank" rel="noopener">View Public Site</a></div>
            @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                <div class="row-card">
                    <h3>{{ $language }}</h3>
                    <div class="grid">
                        @foreach (['platform_name' => 'Platform Name', 'title' => 'Title', 'primary_button' => 'Primary Button', 'secondary_button' => 'Secondary Button'] as $key => $label)
                            <label>{{ $label }}<input name="settings[hero][{{ $key }}][{{ $locale }}]" value="{{ old("settings.hero.$key.$locale", $settingText($hero, $key, $locale)) }}" required></label>
                        @endforeach
                        <label>Introduction<textarea name="settings[hero][introduction][{{ $locale }}]" required>{{ old("settings.hero.introduction.$locale", $settingText($hero, 'introduction', $locale)) }}</textarea></label>
                        <label>Banner Text<textarea name="settings[hero][banner_text][{{ $locale }}]">{{ old("settings.hero.banner_text.$locale", $settingText($hero, 'banner_text', $locale)) }}</textarea></label>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><div><h2>Landing and Registration Sections</h2><p>The Important Notice shown beside Student Information is managed here.</p></div><a class="btn light" href="{{ route('student-registrations.create') }}" target="_blank" rel="noopener">View Form</a></div>
            @foreach ($cmsSections as $key => $sectionData)
                @php($section = $sectionData['model'])
                <div class="row-card">
                    <h3>{{ $sectionData['label'] }}</h3>
                    <input type="hidden" name="sections[{{ $key }}][sort_order]" value="{{ $section?->sort_order ?? $loop->index * 10 }}">
                    @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                        <h4 style="margin:18px 0 10px">{{ $language }}</h4>
                        <div class="grid">
                            <label>Eyebrow / Badge<input name="sections[{{ $key }}][eyebrow][{{ $locale }}]" value="{{ old("sections.$key.eyebrow.$locale", $modelText($section, 'eyebrow', $locale)) }}"></label>
                            <label>Title<input name="sections[{{ $key }}][title][{{ $locale }}]" value="{{ old("sections.$key.title.$locale", $modelText($section, 'title', $locale)) }}" required></label>
                            <label>Body<textarea name="sections[{{ $key }}][body][{{ $locale }}]" required>{{ old("sections.$key.body.$locale", $modelText($section, 'body', $locale)) }}</textarea></label>
                            <label>Items <span class="hint">One item per line.</span><textarea name="sections[{{ $key }}][items][{{ $locale }}]">{{ old("sections.$key.items.$locale", $modelText($section, 'items', $locale)) }}</textarea></label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><div><h2>Registration Schedule and Test Site</h2><p>Used on both the homepage and no-login registration form.</p></div></div>
            @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                <div class="row-card">
                    <h3>{{ $language }} Registration Periods</h3>
                    <div class="grid">
                        @foreach (['main_period' => 'Main Registration Period', 'late_period' => 'Late Registration Period', 'main_test_period' => 'Main Test Period', 'late_test_period' => 'Late-Testing Period'] as $key => $label)
                            <label>{{ $label }}<input name="registration_settings[{{ $key }}][{{ $locale }}]" value="{{ old("registration_settings.$key.$locale", $registrationText($key, $locale)) }}" required></label>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div class="row-card">
                <h3>Test Site</h3>
                <div class="grid">
                    <label>English Name<input name="registration_settings[test_site_name_en][en]" value="{{ old('registration_settings.test_site_name_en.en', $registrationText('test_site_name_en', 'en')) }}" required></label>
                    <label>Traditional Chinese Name<input name="registration_settings[test_site_name_zh][zh_TW]" value="{{ old('registration_settings.test_site_name_zh.zh_TW', $registrationText('test_site_name_zh', 'zh_TW')) }}" required></label>
                    <label>English Address<input name="registration_settings[test_site_address_en][en]" value="{{ old('registration_settings.test_site_address_en.en', $registrationText('test_site_address_en', 'en')) }}" required></label>
                    <label>Traditional Chinese Address<input name="registration_settings[test_site_address_zh][zh_TW]" value="{{ old('registration_settings.test_site_address_zh.zh_TW', $registrationText('test_site_address_zh', 'zh_TW')) }}" required></label>
                </div>
                <input type="hidden" name="registration_settings[test_site_name_en][zh_TW]" value="{{ old('registration_settings.test_site_name_en.zh_TW', $registrationText('test_site_name_en', 'en')) }}">
                <input type="hidden" name="registration_settings[test_site_name_zh][en]" value="{{ old('registration_settings.test_site_name_zh.en', $registrationText('test_site_name_zh', 'zh_TW')) }}">
                <input type="hidden" name="registration_settings[test_site_address_en][zh_TW]" value="{{ old('registration_settings.test_site_address_en.zh_TW', $registrationText('test_site_address_en', 'en')) }}">
                <input type="hidden" name="registration_settings[test_site_address_zh][en]" value="{{ old('registration_settings.test_site_address_zh.en', $registrationText('test_site_address_zh', 'zh_TW')) }}">
            </div>
            <label>Test Site Map URL<input name="registration_settings[test_site_map_url][en]" value="{{ old('registration_settings.test_site_map_url.en', $registrationText('test_site_map_url', 'en')) }}" type="url" required></label>
            <input type="hidden" name="registration_settings[test_site_map_url][zh_TW]" value="{{ old('registration_settings.test_site_map_url.zh_TW', $registrationText('test_site_map_url', 'en')) }}">
        </section>

        <section class="card">
            <div class="section-title"><h2>Registration Timeline</h2></div>
            @foreach ($timelines as $index => $item)
                <div class="row-card">
                    <div class="grid">
                        @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                            <label>{{ $language }} Round<input name="timelines[{{ $index }}][round][{{ $locale }}]" value="{{ old("timelines.$index.round.$locale", $modelText($item, 'round', $locale)) }}" required></label>
                            <label>{{ $language }} Month<input name="timelines[{{ $index }}][month][{{ $locale }}]" value="{{ old("timelines.$index.month.$locale", $modelText($item, 'month', $locale)) }}" required></label>
                            <label>{{ $language }} Description<textarea name="timelines[{{ $index }}][description][{{ $locale }}]">{{ old("timelines.$index.description.$locale", $modelText($item, 'description', $locale)) }}</textarea></label>
                        @endforeach
                        <label>Status<select name="timelines[{{ $index }}][status]" required>@foreach (['Open', 'Upcoming', 'Closed'] as $status)<option value="{{ $status }}" @selected(old("timelines.$index.status", $item->status) === $status)>{{ $status }}</option>@endforeach</select></label>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Fee Information Copy</h2></div>
            @foreach ($fees as $index => $fee)
                <div class="row-card grid">
                    @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                        <label>{{ $language }} Name<input name="fees[{{ $index }}][name][{{ $locale }}]" value="{{ old("fees.$index.name.$locale", $modelText($fee, 'name', $locale)) }}" required></label>
                        <label>{{ $language }} Description<textarea name="fees[{{ $index }}][description][{{ $locale }}]">{{ old("fees.$index.description.$locale", $modelText($fee, 'description', $locale)) }}</textarea></label>
                    @endforeach
                    <label>Currency<input name="fees[{{ $index }}][currency]" value="{{ old("fees.$index.currency", $fee->currency) }}" required></label>
                    <label>Amount<input name="fees[{{ $index }}][amount]" value="{{ old("fees.$index.amount", $fee->amount) }}" type="number" min="0" required></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Required Documents</h2></div>
            @foreach ($documents as $index => $document)
                <div class="row-card grid">
                    @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                        <label>{{ $language }} Name<input name="documents[{{ $index }}][name][{{ $locale }}]" value="{{ old("documents.$index.name.$locale", $modelText($document, 'name', $locale)) }}" required></label>
                        <label>{{ $language }} Description<textarea name="documents[{{ $index }}][description][{{ $locale }}]">{{ old("documents.$index.description.$locale", $modelText($document, 'description', $locale)) }}</textarea></label>
                    @endforeach
                    <label>Required<select name="documents[{{ $index }}][is_required]"><option value="1" @selected($document->is_required)>Yes</option><option value="0" @selected(! $document->is_required)>No</option></select></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><div><h2>Frequently Asked Questions</h2><p>Add, remove, and translate public FAQ entries.</p></div><button class="btn light" type="button" id="addFaq">Add FAQ</button></div>
            <div id="faqRows">
                @foreach ($faqs as $index => $faq)
                    <div class="row-card faq-editor-row grid">
                        @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                            <label>{{ $language }} Question<input name="faqs[{{ $index }}][question][{{ $locale }}]" value="{{ old("faqs.$index.question.$locale", $modelText($faq, 'question', $locale)) }}"></label>
                            <label>{{ $language }} Answer<textarea name="faqs[{{ $index }}][answer][{{ $locale }}]">{{ old("faqs.$index.answer.$locale", $modelText($faq, 'answer', $locale)) }}</textarea></label>
                        @endforeach
                        <button class="btn danger remove-faq" type="button">Remove</button>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card">
            <div class="section-title"><h2>Contact Information</h2></div>
            <div class="grid">
                @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                    <label>{{ $language }} Organization<input name="contact[organization][{{ $locale }}]" value="{{ old("contact.organization.$locale", $modelText($contact, 'organization', $locale)) }}" required></label>
                    <label>{{ $language }} Office Hours<input name="contact[office_hours][{{ $locale }}]" value="{{ old("contact.office_hours.$locale", $modelText($contact, 'office_hours', $locale)) }}"></label>
                    <label>{{ $language }} Address<textarea name="contact[address][{{ $locale }}]">{{ old("contact.address.$locale", $modelText($contact, 'address', $locale)) }}</textarea></label>
                    <label>{{ $language }} Social Links <span class="hint">One per line.</span><textarea name="contact[social_links][{{ $locale }}]">{{ old("contact.social_links.$locale", $modelText($contact, 'social_links', $locale)) }}</textarea></label>
                @endforeach
                <label>Email<input name="contact[email]" value="{{ old('contact.email', $contact?->email) }}" type="email"></label>
                <label>Phone<input name="contact[phone]" value="{{ old('contact.phone', $contact?->phone) }}"></label>
                <label>Line / WhatsApp<input name="contact[whatsapp]" value="{{ old('contact.whatsapp', $contact?->whatsapp) }}"></label>
                <label>Google Map URL<input name="contact[map_url]" value="{{ old('contact.map_url', $contact?->map_url) }}" type="url"></label>
            </div>
        </section>

        <button class="btn" type="submit">Save English and Chinese Content</button>
    </form>

    <template id="faqTemplate">
        <div class="row-card faq-editor-row grid">
            @foreach (['en' => 'English', 'zh_TW' => 'Traditional Chinese'] as $locale => $language)
                <label>{{ $language }} Question<input name="faqs[__INDEX__][question][{{ $locale }}]"></label>
                <label>{{ $language }} Answer<textarea name="faqs[__INDEX__][answer][{{ $locale }}]"></textarea></label>
            @endforeach
            <button class="btn danger remove-faq" type="button">Remove</button>
        </div>
    </template>
    <script>
        (() => {
            const rows = document.getElementById('faqRows');
            const template = document.getElementById('faqTemplate');
            document.getElementById('addFaq').addEventListener('click', () => {
                const index = rows.querySelectorAll('.faq-editor-row').length;
                rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', index));
            });
            rows.addEventListener('click', (event) => {
                const button = event.target.closest('.remove-faq');
                if (button) button.closest('.faq-editor-row').remove();
            });
        })();
    </script>
</x-admin-shell>
