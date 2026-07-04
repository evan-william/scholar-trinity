@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
@endphp
<x-admin-shell
    :title="__('landing.admin_title')"
    subtitle="Website / Landing Page / Program Overview / Timeline / Fee Settings / Documents / FAQ / Contact / SEO"
>

    <form method="POST" action="{{ route('admin.landing.update') }}">
        @csrf
        @method('PUT')

        <section class="card">
            <div class="section-title"><h2>SEO Metadata</h2></div>
            <div class="grid">
                <label>Meta Title<input name="settings[seo][meta_title]" value="{{ old('settings.seo.meta_title', data_get($seo, 'meta_title.text')) }}" maxlength="70" required></label>
                <label>Keywords<input name="settings[seo][keywords]" value="{{ old('settings.seo.keywords', data_get($seo, 'keywords.text')) }}" maxlength="255"></label>
                <label>Canonical URL<input name="settings[seo][canonical_url]" value="{{ old('settings.seo.canonical_url', data_get($seo, 'canonical_url.text')) }}" type="url"></label>
                <label>Meta Description<textarea name="settings[seo][meta_description]" maxlength="170" required>{{ old('settings.seo.meta_description', data_get($seo, 'meta_description.text')) }}</textarea></label>
            </div>
        </section>

        <section class="card">
            <div class="section-title"><h2>Hero Banner</h2></div>
            <div class="grid">
                <label>Platform Name<input name="settings[hero][platform_name]" value="{{ old('settings.hero.platform_name', data_get($hero, 'platform_name.text')) }}" required></label>
                <label>Title<input name="settings[hero][title]" value="{{ old('settings.hero.title', data_get($hero, 'title.text')) }}" required></label>
                <label>Primary Button<input name="settings[hero][primary_button]" value="{{ old('settings.hero.primary_button', data_get($hero, 'primary_button.text')) }}" required></label>
                <label>Secondary Button<input name="settings[hero][secondary_button]" value="{{ old('settings.hero.secondary_button', data_get($hero, 'secondary_button.text')) }}" required></label>
                <label>Introduction<textarea name="settings[hero][introduction]" required>{{ old('settings.hero.introduction', data_get($hero, 'introduction.text')) }}</textarea></label>
                <label>Banner Text<textarea name="settings[hero][banner_text]">{{ old('settings.hero.banner_text', data_get($hero, 'banner_text.text')) }}</textarea></label>
            </div>
        </section>

        <section class="card">
            <div class="section-title"><h2>CMS Sections</h2></div>
            @foreach (['overview' => $overview, 'process' => $process, 'privacy' => $privacy] as $key => $section)
                <div class="row-card">
                    <h3>{{ ucfirst($key) }}</h3>
                    <input type="hidden" name="sections[{{ $key }}][sort_order]" value="{{ $section?->sort_order ?? $loop->index * 10 }}">
                    <div class="grid">
                        <label>Eyebrow<input name="sections[{{ $key }}][eyebrow]" value="{{ old("sections.$key.eyebrow", $section?->eyebrow) }}"></label>
                        <label>Title<input name="sections[{{ $key }}][title]" value="{{ old("sections.$key.title", $section?->title) }}" required></label>
                        <label>Body<textarea name="sections[{{ $key }}][body]" required>{{ old("sections.$key.body", $section?->body) }}</textarea></label>
                        <label>Items <span class="hint">One item per line.</span><textarea name="sections[{{ $key }}][items]">{{ old("sections.$key.items", implode("\n", $section?->items ?? [])) }}</textarea></label>
                    </div>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Registration Timeline</h2></div>
            @foreach ($timelines as $index => $item)
                <div class="row-card grid-3">
                    <label>Round<input name="timelines[{{ $index }}][round]" value="{{ old("timelines.$index.round", $item->round) }}" required></label>
                    <label>Month<input name="timelines[{{ $index }}][month]" value="{{ old("timelines.$index.month", $item->month) }}" required></label>
                    <label>Status<select name="timelines[{{ $index }}][status]" required>
                        @foreach (['Open', 'Upcoming', 'Closed'] as $status)
                            <option value="{{ $status }}" @selected(old("timelines.$index.status", $item->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select></label>
                    <label style="grid-column:1/-1;">Description<textarea name="timelines[{{ $index }}][description]">{{ old("timelines.$index.description", $item->description) }}</textarea></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Fee Information</h2></div>
            @foreach ($fees as $index => $fee)
                <div class="row-card grid">
                    <label>Name<input name="fees[{{ $index }}][name]" value="{{ old("fees.$index.name", $fee->name) }}" required></label>
                    <label>Currency<input name="fees[{{ $index }}][currency]" value="{{ old("fees.$index.currency", $fee->currency) }}" required></label>
                    <label>Amount<input name="fees[{{ $index }}][amount]" value="{{ old("fees.$index.amount", $fee->amount) }}" type="number" min="0" required></label>
                    <label>Description<textarea name="fees[{{ $index }}][description]">{{ old("fees.$index.description", $fee->description) }}</textarea></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Required Documents</h2></div>
            @foreach ($documents as $index => $document)
                <div class="row-card grid">
                    <label>Name<input name="documents[{{ $index }}][name]" value="{{ old("documents.$index.name", $document->name) }}" required></label>
                    <label>Required<select name="documents[{{ $index }}][is_required]">
                        <option value="1" @selected($document->is_required)>Required</option>
                        <option value="0" @selected(! $document->is_required)>Optional</option>
                    </select></label>
                    <label style="grid-column:1/-1;">Description<textarea name="documents[{{ $index }}][description]">{{ old("documents.$index.description", $document->description) }}</textarea></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Frequently Asked Questions</h2></div>
            @foreach ($faqs as $index => $faq)
                <div class="row-card grid">
                    <label>Question<input name="faqs[{{ $index }}][question]" value="{{ old("faqs.$index.question", $faq->question) }}" required></label>
                    <label>Answer<textarea name="faqs[{{ $index }}][answer]" required>{{ old("faqs.$index.answer", $faq->answer) }}</textarea></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <div class="section-title"><h2>Contact Information</h2></div>
            <div class="grid">
                <label>Organization<input name="contact[organization]" value="{{ old('contact.organization', $contact?->organization) }}" required></label>
                <label>Email<input name="contact[email]" value="{{ old('contact.email', $contact?->email) }}" type="email"></label>
                <label>Phone<input name="contact[phone]" value="{{ old('contact.phone', $contact?->phone) }}"></label>
                <label>WhatsApp<input name="contact[whatsapp]" value="{{ old('contact.whatsapp', $contact?->whatsapp) }}"></label>
                <label>Office Hours<input name="contact[office_hours]" value="{{ old('contact.office_hours', $contact?->office_hours) }}"></label>
                <label>Google Map URL<input name="contact[map_url]" value="{{ old('contact.map_url', $contact?->map_url) }}" type="url"></label>
                <label>Address<textarea name="contact[address]">{{ old('contact.address', $contact?->address) }}</textarea></label>
                <label>Social Media <span class="hint">One link per line.</span><textarea name="contact[social_links]">{{ old('contact.social_links', implode("\n", $contact?->social_links ?? [])) }}</textarea></label>
            </div>
        </section>

        <div class="actions">
            <a class="btn light" href="{{ route('landing') }}">View Landing Page</a>
            <button class="btn" type="submit">{{ __('landing.save_changes') }}</button>
        </div>
    </form>
</x-admin-shell>
