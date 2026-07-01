@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('landing.admin_title') }}</title>
    <style>
        :root { --primary:#153764; --accent:#c9a84c; --ink:#1f2a37; --muted:#667085; --line:#d9dee8; --soft:#f5f7fb; --danger:#b42318; --success:#237a4f; }
        * { box-sizing:border-box; }
        body { margin:0; background:var(--soft); color:var(--ink); font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei",Arial,sans-serif; }
        header { background:var(--primary); color:white; padding:18px 24px; }
        header h1 { margin:0; font-size:20px; }
        header p { margin:4px 0 0; opacity:.78; font-size:13px; }
        main { max-width:1080px; margin:0 auto; padding:24px 16px 60px; }
        .notice { margin-bottom:16px; padding:12px 14px; border-radius:8px; background:#e8f6ef; color:var(--success); border:1px solid #bfe6ce; font-weight:800; }
        .error { margin-bottom:16px; padding:12px 14px; border-radius:8px; background:#fff0ee; color:var(--danger); border:1px solid #ffc9c4; }
        .card { background:white; border:1px solid var(--line); border-radius:8px; padding:20px; margin-bottom:16px; box-shadow:0 4px 16px rgba(22,47,83,.05); }
        h2 { margin:0 0 16px; color:var(--primary); border-bottom:2px solid var(--accent); padding-bottom:8px; font-size:18px; }
        h3 { margin:16px 0 10px; color:var(--primary); font-size:15px; }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
        .grid-3 { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:14px; }
        label { display:flex; flex-direction:column; gap:6px; font-size:13px; font-weight:800; color:var(--ink); }
        input, textarea, select { width:100%; min-height:40px; border:1.5px solid #cbd3df; border-radius:6px; padding:9px 11px; font:inherit; font-size:14px; }
        textarea { min-height:96px; resize:vertical; }
        input:focus, textarea:focus, select:focus { outline:0; border-color:var(--primary); box-shadow:0 0 0 3px rgba(21,55,100,.1); }
        .row-card { padding:14px; border:1px solid #edf0f5; border-radius:8px; background:#fbfcfe; margin-bottom:10px; }
        .hint { color:var(--muted); font-size:12px; font-weight:400; line-height:1.5; }
        .actions { position:sticky; bottom:0; display:flex; justify-content:space-between; align-items:center; gap:12px; margin-top:18px; padding:14px 0; background:var(--soft); }
        .btn { border:0; border-radius:6px; padding:11px 18px; font:inherit; font-weight:900; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; justify-content:center; }
        .btn-primary { background:var(--primary); color:white; }
        .btn-secondary { background:white; color:var(--primary); border:1.5px solid var(--line); }
        @@media(max-width:760px){ .grid,.grid-3{ grid-template-columns:1fr; } }
    </style>
</head>
<body>
<header>
    <h1>{{ __('landing.admin_title') }}</h1>
    <p>Website / Landing Page / Program Overview / Timeline / Fee Settings / Documents / FAQ / Contact / SEO</p>
</header>
<main>
    @if (session('status'))
        <div class="notice">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="error">
            <strong>Please fix the validation errors.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.landing.update') }}">
        @csrf
        @method('PUT')

        <section class="card">
            <h2>SEO Metadata</h2>
            <div class="grid">
                <label>Meta Title<input name="settings[seo][meta_title]" value="{{ old('settings.seo.meta_title', data_get($seo, 'meta_title.text')) }}" maxlength="70" required></label>
                <label>Keywords<input name="settings[seo][keywords]" value="{{ old('settings.seo.keywords', data_get($seo, 'keywords.text')) }}" maxlength="255"></label>
                <label>Canonical URL<input name="settings[seo][canonical_url]" value="{{ old('settings.seo.canonical_url', data_get($seo, 'canonical_url.text')) }}" type="url"></label>
                <label>Meta Description<textarea name="settings[seo][meta_description]" maxlength="170" required>{{ old('settings.seo.meta_description', data_get($seo, 'meta_description.text')) }}</textarea></label>
            </div>
        </section>

        <section class="card">
            <h2>Hero Banner</h2>
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
            <h2>CMS Sections</h2>
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
            <h2>Registration Timeline</h2>
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
            <h2>Fee Information</h2>
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
            <h2>Required Documents</h2>
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
            <h2>Frequently Asked Questions</h2>
            @foreach ($faqs as $index => $faq)
                <div class="row-card grid">
                    <label>Question<input name="faqs[{{ $index }}][question]" value="{{ old("faqs.$index.question", $faq->question) }}" required></label>
                    <label>Answer<textarea name="faqs[{{ $index }}][answer]" required>{{ old("faqs.$index.answer", $faq->answer) }}</textarea></label>
                </div>
            @endforeach
        </section>

        <section class="card">
            <h2>Contact Information</h2>
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
            <a class="btn btn-secondary" href="{{ route('landing') }}">View Landing Page</a>
            <button class="btn btn-primary" type="submit">{{ __('landing.save_changes') }}</button>
        </div>
    </form>
</main>
</body>
</html>
