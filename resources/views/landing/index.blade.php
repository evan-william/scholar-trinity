@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $metaTitle = data_get($seo, 'meta_title.text', 'AP Exam Registration');
    $metaDescription = data_get($seo, 'meta_description.text', 'AP Exam Registration Platform');
    $canonical = data_get($seo, 'canonical_url.text', url()->current());
    $heroTitle = data_get($hero, 'title.text', 'AP Exam Registration');
    $platformName = data_get($hero, 'platform_name.text', 'TPCA x Trinity Scholar');
    $heroIntro = data_get($hero, 'introduction.text', '');
    $primaryButton = data_get($hero, 'primary_button.text', __('landing.register_now'));
    $secondaryButton = data_get($hero, 'secondary_button.text', __('landing.learn_more'));
    $bannerText = data_get($hero, 'banner_text.text', '');
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
    $feeTotal = $fees->sum('amount');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="keywords" content="{{ data_get($seo, 'keywords.text', '') }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $canonical }}">
    <meta name="theme-color" content="#1a3a6b">
    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@@type": "EducationalOrganization",
            "name": "{{ $platformName }}",
            "url": "{{ $canonical }}",
            "description": "{{ $metaDescription }}"
        }
    </script>
    <style>
        :root {
            color-scheme: light;
            --primary: #153764;
            --primary-2: #25558f;
            --accent: #c9a84c;
            --ink: #1f2a37;
            --muted: #657286;
            --line: #d9dee8;
            --soft: #f5f7fb;
            --white: #fff;
            --success: #237a4f;
            --warning: #9a6a00;
            --closed: #8a3b35;
            --radius: 8px;
            --shadow: 0 18px 50px rgba(22, 47, 83, .12);
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { margin: 0; min-height: 100vh; color: var(--ink); background: var(--soft); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", "Microsoft JhengHei", Arial, sans-serif; }
        a { color: inherit; }
        .skip { position:absolute; left:-999px; top:10px; background:white; color:var(--primary); padding:10px 14px; z-index:10; }
        .skip:focus { left:10px; }
        .nav { position: sticky; top: 0; z-index: 20; background: rgba(255,255,255,.94); border-bottom: 1px solid rgba(217,222,232,.9); backdrop-filter: blur(12px); }
        .nav-inner { max-width: 1120px; margin: 0 auto; padding: 12px 20px; display: flex; align-items: center; gap: 18px; }
        .brand { display: flex; align-items: center; gap: 10px; min-width: 220px; font-weight: 900; color: var(--primary); }
        .mark { width: 38px; height: 38px; border-radius: 7px; display: grid; place-items: center; background: var(--primary); color: white; font-size: 12px; line-height: 1.05; text-align: center; }
        .links { display: flex; gap: 18px; margin-left: auto; align-items: center; font-size: 13px; color: var(--muted); }
        .links a { text-decoration: none; font-weight: 700; }
        .language-switcher select { min-height: 38px; border: 1.5px solid var(--line); border-radius: 6px; color: var(--primary); font-weight: 800; background: white; padding: 7px 10px; }
        .sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}
        .nav-cta { padding: 9px 14px; border-radius: 6px; background: var(--primary); color: white !important; }
        .hero { background: linear-gradient(180deg, #ffffff 0%, #eef3f9 100%); border-bottom: 1px solid var(--line); }
        .hero-inner { max-width: 1120px; margin: 0 auto; padding: 54px 20px 34px; display: grid; grid-template-columns: minmax(0, 1.04fr) minmax(320px, .96fr); gap: 34px; align-items: center; }
        .eyebrow { color: var(--primary-2); font-size: 12px; font-weight: 900; letter-spacing: .08em; text-transform: uppercase; }
        h1 { margin: 10px 0 14px; color: var(--primary); font-size: clamp(38px, 6vw, 70px); line-height: .98; letter-spacing: 0; }
        .lead { color: #4c5d72; font-size: 17px; line-height: 1.7; max-width: 620px; }
        .hero-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 24px; }
        .btn { display: inline-flex; align-items: center; justify-content: center; min-height: 44px; padding: 11px 18px; border-radius: 6px; text-decoration: none; border: 1.5px solid transparent; font-weight: 900; font-size: 14px; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { color: var(--primary); background: white; border-color: var(--line); }
        .hero-panel { position: relative; min-height: 410px; border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow); background: #102b50; }
        .hero-art { position: absolute; inset: 0; background:
            linear-gradient(135deg, rgba(21,55,100,.96), rgba(38,86,144,.72)),
            radial-gradient(circle at 70% 18%, rgba(201,168,76,.46), transparent 31%),
            linear-gradient(90deg, transparent 0 68%, rgba(255,255,255,.08) 68% 69%, transparent 69%),
            repeating-linear-gradient(0deg, rgba(255,255,255,.08), rgba(255,255,255,.08) 1px, transparent 1px, transparent 30px);
        }
        .hero-card { position: absolute; left: 28px; right: 28px; bottom: 28px; padding: 20px; border: 1px solid rgba(255,255,255,.24); border-radius: 8px; background: rgba(255,255,255,.12); color: white; backdrop-filter: blur(8px); }
        .hero-card strong { display:block; font-size: 19px; margin-bottom: 8px; }
        .hero-card span { display:block; font-size: 13px; line-height: 1.6; opacity: .9; }
        .stats { max-width: 1120px; margin: 0 auto; padding: 0 20px 34px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
        .stat { padding: 16px; border-radius: 8px; background: white; border: 1px solid var(--line); }
        .stat strong { display:block; color: var(--primary); font-size: 20px; }
        .stat span { color: var(--muted); font-size: 12px; }
        main { max-width: 1120px; margin: 0 auto; padding: 38px 20px 60px; }
        section { scroll-margin-top: 82px; }
        .section-head { display: flex; align-items: end; justify-content: space-between; gap: 16px; margin-bottom: 18px; }
        .section-head h2 { margin: 0; color: var(--primary); font-size: 28px; }
        .section-head p { max-width: 620px; margin: 6px 0 0; color: var(--muted); line-height: 1.65; }
        .band { padding: 28px 0; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .grid-2 { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .card { padding: 20px; border-radius: var(--radius); background: white; border: 1px solid var(--line); box-shadow: 0 4px 16px rgba(22,47,83,.05); }
        .card h3 { margin: 0 0 8px; color: var(--primary); font-size: 16px; }
        .card p, .card li { color: var(--muted); font-size: 13px; line-height: 1.65; }
        .card ul { margin: 12px 0 0; padding-left: 18px; }
        .timeline { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .timeline-group h3 { margin: 0 0 10px; color: var(--primary); }
        .timeline-item { display: grid; grid-template-columns: 88px 1fr auto; gap: 12px; align-items: start; padding: 14px 0; border-top: 1px solid #edf0f5; }
        .month { font-weight: 900; color: var(--ink); }
        .status { display:inline-flex; align-items:center; justify-content:center; min-width: 78px; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 900; }
        .status.Open { color: var(--success); background: #e8f6ef; }
        .status.Upcoming { color: var(--warning); background: #fff6dc; }
        .status.Closed { color: var(--closed); background: #fff0ee; }
        .workflow { counter-reset: step; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px; }
        .workflow-item { position: relative; padding: 16px 14px 16px 48px; border-radius: 8px; background: white; border: 1px solid var(--line); min-height: 76px; font-weight: 800; color: var(--primary); }
        .workflow-item::before { counter-increment: step; content: counter(step); position:absolute; left:14px; top:15px; width:24px; height:24px; display:grid; place-items:center; border-radius:50%; color:white; background:var(--primary); font-size:12px; }
        .fee-card { display:flex; justify-content:space-between; gap:16px; align-items:start; }
        .amount { color: var(--primary); font-size: 22px; font-weight: 900; white-space: nowrap; }
        .total-card { border-color: rgba(201,168,76,.65); background: #fffbef; }
        .doc { display:flex; gap:10px; align-items:flex-start; }
        .check { width:22px; height:22px; min-width:22px; display:grid; place-items:center; border-radius:50%; background:#e8f6ef; color:var(--success); font-weight:900; }
        details { padding: 16px 18px; border: 1px solid var(--line); border-radius: 8px; background: white; }
        details + details { margin-top: 10px; }
        summary { cursor: pointer; color: var(--primary); font-weight: 900; }
        details p { color: var(--muted); line-height: 1.65; }
        .contact-list { display:grid; gap:10px; }
        .contact-list div { padding-bottom:10px; border-bottom:1px solid #edf0f5; font-size:13px; }
        .contact-list strong { display:block; color:var(--primary); margin-bottom:3px; }
        .privacy-links { display:flex; gap:10px; flex-wrap:wrap; margin-top:14px; }
        .privacy-links a { color:var(--primary); font-weight:900; }
        .cta { margin-top: 34px; padding: 34px 24px; border-radius: 8px; background: var(--primary); color: white; display:flex; align-items:center; justify-content:space-between; gap:24px; }
        .cta h2 { margin:0 0 8px; font-size:30px; }
        .cta p { margin:0; color:rgba(255,255,255,.82); line-height:1.65; }
        .cta .btn { background:white; color:var(--primary); min-width:150px; }
        @@media (max-width: 860px) {
            .hero-inner { grid-template-columns: 1fr; padding-top: 34px; }
            .hero-panel { min-height: 300px; }
            .links a:not(.nav-cta) { display:none; }
            .stats, .grid-3, .grid-2, .timeline, .workflow { grid-template-columns: 1fr; }
            .workflow-item { min-height: auto; }
            .cta { flex-direction: column; align-items: flex-start; }
        }
        @@media (max-width: 520px) {
            .nav-inner { padding: 10px 14px; }
            .brand { min-width: 0; font-size: 13px; }
            h1 { font-size: 40px; }
            .hero-inner, main { padding-left: 14px; padding-right: 14px; }
            .timeline-item { grid-template-columns: 1fr; }
            .status { justify-content:flex-start; width:max-content; }
        }
    </style>
</head>
<body>
<a href="#main" class="skip">Skip to content</a>
<nav class="nav" aria-label="Primary navigation">
    <div class="nav-inner">
        <div class="brand"><span class="mark">AP<br>EXAM</span><span>{{ $platformName }}</span></div>
        <div class="links">
            <a href="#overview">{{ __('landing.nav_overview') }}</a>
            <a href="#timeline">{{ __('landing.nav_timeline') }}</a>
            <a href="#fees">{{ __('landing.nav_fees') }}</a>
            <a href="#faq">{{ __('landing.nav_faq') }}</a>
            <x-language-switcher />
            <a class="nav-cta" href="{{ route('student-registrations.create') }}">{{ $primaryButton }}</a>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="hero-inner">
        <div>
            <div class="eyebrow">{{ $platformName }}</div>
            <h1>{{ $heroTitle }}</h1>
            <p class="lead">{{ $heroIntro }}</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="{{ route('student-registrations.create') }}">{{ $primaryButton }}</a>
                <a class="btn btn-secondary" href="#overview">{{ $secondaryButton }}</a>
            </div>
        </div>
        <div class="hero-panel" aria-label="AP registration platform overview illustration">
            <div class="hero-art"></div>
            <div class="hero-card">
                <strong>{{ $heroTitle }}</strong>
                <span>{{ $bannerText }}</span>
            </div>
        </div>
    </div>
    <div class="stats" aria-label="Registration highlights">
        <div class="stat"><strong>Aug-Oct</strong><span>Main registration</span></div>
        <div class="stat"><strong>Jan-Mar</strong><span>Late registration</span></div>
        <div class="stat"><strong>10MB</strong><span>Passport upload limit</span></div>
        <div class="stat"><strong>NTD</strong><span>Local fee display</span></div>
    </div>
</header>

<main id="main">
    <section id="overview" class="band" aria-labelledby="overview-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">{{ $overview?->eyebrow }}</div>
                <h2 id="overview-title">{{ $overview?->title }}</h2>
                <p>{{ $overview?->body }}</p>
            </div>
        </div>
        <div class="grid-3">
            @foreach (($overview?->items ?? []) as $item)
                <div class="card"><h3>{{ $item }}</h3><p>{{ $item }}</p></div>
            @endforeach
        </div>
    </section>

    <section id="timeline" class="band" aria-labelledby="timeline-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">{{ __('landing.current_status') }}</div>
                <h2 id="timeline-title">{{ __('landing.registration_timeline') }}</h2>
            </div>
        </div>
        <div class="timeline">
            @foreach ($timelines as $round => $items)
                <div class="card timeline-group">
                    <h3>{{ $round }}</h3>
                    @foreach ($items as $item)
                        <div class="timeline-item">
                            <div class="month">{{ $item->month }}</div>
                            <div><p>{{ $item->description }}</p></div>
                            <span class="status {{ $item->status }}">{{ $item->status }}</span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="band" aria-labelledby="process-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">{{ $process?->eyebrow }}</div>
                <h2 id="process-title">{{ $process?->title }}</h2>
                <p>{{ $process?->body }}</p>
            </div>
        </div>
        <div class="workflow">
            @foreach (($process?->items ?? []) as $item)
                <div class="workflow-item">{{ $item }}</div>
            @endforeach
        </div>
    </section>

    <section id="fees" class="band" aria-labelledby="fees-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">NTD</div>
                <h2 id="fees-title">{{ __('landing.exam_fee') }}</h2>
            </div>
        </div>
        <div class="grid-3">
            @foreach ($fees as $fee)
                <div class="card fee-card">
                    <div><h3>{{ $fee->name }}</h3><p>{{ $fee->description }}</p></div>
                    <div class="amount">{{ $fee->currency }} {{ number_format($fee->amount) }}</div>
                </div>
            @endforeach
            <div class="card fee-card total-card">
                <div><h3>Total</h3><p>Estimated total before any subject-specific adjustment.</p></div>
                <div class="amount">NTD {{ number_format($feeTotal) }}</div>
            </div>
        </div>
    </section>

    <section class="band" aria-labelledby="documents-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">Checklist</div>
                <h2 id="documents-title">{{ __('landing.required_documents') }}</h2>
            </div>
        </div>
        <div class="grid-3">
            @foreach ($documents as $document)
                <div class="card doc">
                    <span class="check" aria-hidden="true">✓</span>
                    <div><h3>{{ $document->name }}</h3><p>{{ $document->description }}</p></div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="faq" class="band" aria-labelledby="faq-title">
        <div class="section-head">
            <div>
                <div class="eyebrow">FAQ</div>
                <h2 id="faq-title">{{ __('landing.frequently_asked_questions') }}</h2>
            </div>
        </div>
        @foreach ($faqs as $faq)
            <details>
                <summary>{{ $faq->question }}</summary>
                <p>{{ $faq->answer }}</p>
            </details>
        @endforeach
    </section>

    <section class="band grid-2" aria-labelledby="contact-title">
        <div class="card">
            <div class="eyebrow">Contact</div>
            <h2 id="contact-title">{{ __('landing.contact_information') }}</h2>
            <div class="contact-list">
                <div><strong>Organization</strong>{{ $contact?->organization }}</div>
                <div><strong>Email</strong>{{ $contact?->email }}</div>
                <div><strong>Phone</strong>{{ $contact?->phone }}</div>
                <div><strong>WhatsApp</strong>{{ $contact?->whatsapp }}</div>
                <div><strong>Office Hours</strong>{{ $contact?->office_hours }}</div>
                <div><strong>Address</strong>{{ $contact?->address }}</div>
            </div>
        </div>
        <div class="card">
            <div class="eyebrow">{{ $privacy?->eyebrow }}</div>
            <h2>{{ $privacy?->title }}</h2>
            <p>{{ $privacy?->body }}</p>
            <ul>
                @foreach (($privacy?->items ?? []) as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
            <div class="privacy-links">
                <a href="#">{{ __('landing.privacy_policy') }}</a>
                <a href="#">{{ __('landing.terms_conditions') }}</a>
            </div>
        </div>
    </section>

    <section class="cta" aria-labelledby="cta-title">
        <div>
            <h2 id="cta-title">{{ __('landing.ready_headline') }}</h2>
            <p>{{ __('landing.ready_body') }}</p>
        </div>
        <a class="btn" href="{{ route('registrations.create') }}">{{ $primaryButton }}</a>
    </section>
</main>
</body>
</html>
