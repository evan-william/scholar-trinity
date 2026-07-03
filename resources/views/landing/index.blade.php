@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $metaTitle = data_get($seo, 'meta_title.text', '2026 AP Exam Registration | Trinity Scholar');
    $metaDescription = data_get($seo, 'meta_description.text', 'Trinity Scholar AP Exam registration service for students in Taipei.');
    $canonical = data_get($seo, 'canonical_url.text', url()->current());
    $heroTitle = data_get($hero, 'title.text', '2026 Advanced Placement (AP) Exam Registration');
    $platformName = data_get($hero, 'platform_name.text', 'Trinity Scholar');
    $heroIntro = data_get($hero, 'introduction.text', 'Trinity Scholar offers hassle-free AP Exam registration service for students who need test-center registration support in Taipei.');
    $primaryButton = data_get($hero, 'primary_button.text', __('landing.register_now'));
    $secondaryButton = data_get($hero, 'secondary_button.text', __('landing.learn_more'));
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
    $feeTotal = $fees->sum('amount');
    $assetBase = 'theme/edification/';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta name="theme-color" content="#183967">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/font-awesome.min.css') }}">
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
        :root{--navy:#153764;--blue:#25558f;--gold:#c9a84c;--ink:#1f2a37;--muted:#657286;--line:#dbe2ee;--soft:#f5f7fb;--white:#fff;--green:#237a4f;--red:#a93c34;--shadow:0 22px 60px rgba(18,44,79,.16)}
        *{box-sizing:border-box}
        html{scroll-behavior:smooth}
        body{margin:0;background:var(--soft);color:var(--ink);font-family:-apple-system,BlinkMacSystemFont,"Segoe UI","Microsoft JhengHei",Arial,sans-serif}
        a{text-decoration:none}
        .topbar{background:#0f2c50;color:#fff;font-size:13px}
        .topbar-inner,.nav-inner,.wrap{max-width:1180px;margin:0 auto}
        .topbar-inner{display:flex;justify-content:space-between;gap:16px;padding:9px 20px;flex-wrap:wrap}
        .topbar a{color:#fff;font-weight:800}
        .nav{position:sticky;top:0;z-index:30;background:rgba(255,255,255,.96);border-bottom:1px solid var(--line);backdrop-filter:blur(10px)}
        .nav-inner{display:flex;align-items:center;gap:18px;padding:14px 20px}
        .brand{display:flex;align-items:center;gap:12px;color:var(--navy);font-weight:950;letter-spacing:.02em;min-width:245px}
        .brand-mark{width:42px;height:42px;border-radius:6px;display:grid;place-items:center;background:var(--navy);color:#fff;font-size:11px;line-height:1.05;text-align:center}
        .nav-links{margin-left:auto;display:flex;gap:18px;align-items:center;font-size:13px}
        .nav-links a{color:var(--muted);font-weight:850}
        .language-switcher select{min-height:38px;border:1px solid var(--line);border-radius:6px;color:var(--navy);font-weight:850;background:#fff;padding:7px 10px}
        .btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:11px 18px;border-radius:6px;font-weight:950;border:1.5px solid transparent}
        .btn-primary{background:var(--navy);color:#fff!important}
        .btn-light{background:#fff;color:var(--navy)!important;border-color:var(--line)}
        .btn-gold{background:var(--gold);color:#382800!important}
        .hero{position:relative;overflow:hidden;background:#122f57;color:#fff}
        .hero::before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(11,31,58,.95),rgba(21,55,100,.86),rgba(21,55,100,.5)),url('{{ asset($assetBase.'images/bg/hero-bg.jpg') }}') center/cover no-repeat}
        .hero-inner{position:relative;max-width:1180px;margin:0 auto;display:grid;grid-template-columns:minmax(0,1.05fr) minmax(330px,.95fr);gap:34px;align-items:center;padding:68px 20px 56px}
        .eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:.12em;font-weight:950;color:#f4d982}
        h1{margin:12px 0 16px;font-size:clamp(38px,5vw,64px);line-height:1.02;letter-spacing:0;color:inherit}
        .lead{font-size:18px;line-height:1.7;color:rgba(255,255,255,.9);max-width:680px}
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap;margin-top:26px}
        .hero-note{margin-top:18px;display:grid;gap:8px;color:rgba(255,255,255,.88);font-size:13px}
        .hero-note strong{color:#fff}
        .poster-card{background:#fff;border-radius:8px;box-shadow:var(--shadow);padding:14px;color:var(--ink)}
        .poster-card img{display:block;width:100%;border-radius:6px;border:1px solid var(--line)}
        .poster-caption{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-top:12px;font-size:13px;color:var(--muted)}
        .status-pill{display:inline-flex;align-items:center;border-radius:999px;background:#fff3cf;color:#6b4700;font-weight:950;padding:5px 10px;white-space:nowrap}
        .quick{background:#fff;border-bottom:1px solid var(--line)}
        .quick-grid{max-width:1180px;margin:0 auto;padding:18px 20px;display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px}
        .quick-item{border:1px solid var(--line);border-radius:8px;padding:16px;background:#fff}
        .quick-item strong{display:block;color:var(--navy);font-size:22px}
        .quick-item span{font-size:12px;color:var(--muted)}
        .wrap{padding:44px 20px 72px}
        section{scroll-margin-top:90px}
        .section-head{display:flex;justify-content:space-between;gap:20px;align-items:end;margin-bottom:18px}
        .section-head h2{margin:6px 0 0;color:var(--navy);font-size:32px;line-height:1.15}
        .section-head p{margin:6px 0 0;max-width:680px;color:var(--muted);line-height:1.7}
        .grid-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
        .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        .card{background:#fff;border:1px solid var(--line);border-radius:8px;box-shadow:0 4px 16px rgba(22,47,83,.05);overflow:hidden}
        .card-pad{padding:22px}
        .card img{width:100%;display:block;aspect-ratio:16/10;object-fit:cover}
        .card h3{margin:0 0 9px;color:var(--navy);font-size:18px}
        .card p,.card li{color:var(--muted);font-size:14px;line-height:1.7}
        .info-strip{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}
        .announcement{border-left:5px solid var(--gold);background:#fffaf0;padding:18px;border-radius:8px}
        .announcement p{margin:0 0 10px;color:#5b430a;line-height:1.7}
        .announcement strong{color:#2a2108}
        .timeline{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
        .timeline-group{padding:20px}
        .timeline-item{display:grid;grid-template-columns:96px 1fr auto;gap:12px;border-top:1px solid #edf1f7;padding:14px 0;align-items:start}
        .month{font-weight:950;color:var(--navy)}
        .status{display:inline-flex;align-items:center;justify-content:center;border-radius:999px;padding:5px 10px;font-size:11px;font-weight:950;background:#eef3f9;color:var(--navy);white-space:nowrap}
        .status.Open{background:#e8f6ef;color:var(--green)}
        .status.Closed{background:#fff0ee;color:var(--red)}
        .process{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;counter-reset:step}
        .process-item{position:relative;padding:18px 16px 18px 52px;border:1px solid var(--line);border-radius:8px;background:#fff;font-weight:950;color:var(--navy);min-height:86px}
        .process-item::before{counter-increment:step;content:counter(step);position:absolute;left:16px;top:17px;width:26px;height:26px;border-radius:50%;display:grid;place-items:center;background:var(--navy);color:#fff;font-size:12px}
        .fee-card{display:flex;justify-content:space-between;gap:16px;align-items:flex-start}
        .amount{font-size:24px;color:var(--navy);font-weight:950;white-space:nowrap}
        .total-card{background:#fffaf0;border-color:#ead28a}
        .doc{display:flex;gap:12px}
        .check{width:24px;height:24px;min-width:24px;border-radius:50%;display:grid;place-items:center;background:#e8f6ef;color:var(--green);font-weight:950}
        details{background:#fff;border:1px solid var(--line);border-radius:8px;padding:16px 18px}
        details+details{margin-top:10px}
        summary{cursor:pointer;color:var(--navy);font-weight:950}
        details p{color:var(--muted);line-height:1.7}
        .cta{margin-top:32px;background:var(--navy);color:#fff;border-radius:8px;padding:34px 26px;display:flex;justify-content:space-between;align-items:center;gap:20px}
        .cta h2{margin:0 0 8px;font-size:30px}
        .cta p{margin:0;color:rgba(255,255,255,.82);line-height:1.7}
        .footer{background:#0f2c50;color:#dbe8f8;padding:24px 20px}
        .footer-inner{max-width:1180px;margin:0 auto;display:flex;justify-content:space-between;gap:18px;flex-wrap:wrap;font-size:13px}
        @media(max-width:900px){.hero-inner,.grid-2,.timeline,.info-strip{grid-template-columns:1fr}.grid-3,.process,.quick-grid{grid-template-columns:1fr 1fr}.nav-links a:not(.btn):not(.language-switcher a){display:none}.hero-inner{padding-top:42px}.poster-card{max-width:620px}}
        @media(max-width:560px){.topbar-inner,.nav-inner,.wrap{padding-left:14px;padding-right:14px}.grid-3,.process,.quick-grid{grid-template-columns:1fr}.brand{min-width:0}.hero-inner{grid-template-columns:1fr;padding-left:14px;padding-right:14px}h1{font-size:38px}.timeline-item{grid-template-columns:1fr}.cta{align-items:flex-start;flex-direction:column}}
    </style>
</head>
<body>
<div class="topbar">
    <div class="topbar-inner">
        <span>Hours: Mon-Fri 9:00-18:00</span>
        <span>Call 886-2-2771-6002 · <a href="mailto:info@trinityscholar.com">info@trinityscholar.com</a></span>
    </div>
</div>
<nav class="nav" aria-label="Primary navigation">
    <div class="nav-inner">
        <a class="brand" href="{{ route('landing') }}"><span class="brand-mark">TS<br>AP</span><span>{{ $platformName }}</span></a>
        <div class="nav-links">
            <a href="#overview">Overview</a>
            <a href="#late-registration">Late Registration</a>
            <a href="#fees">Fees</a>
            <a href="#faq">FAQ</a>
            <x-language-switcher />
            <a class="btn btn-primary" href="{{ route('student-registrations.create') }}">{{ $primaryButton }}</a>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="hero-inner">
        <div>
            <div class="eyebrow">Taipei Test Center Support</div>
            <h1>{{ $heroTitle }}</h1>
            <p class="lead">{{ $heroIntro }}</p>
            <div class="hero-actions">
                <a class="btn btn-gold" href="{{ route('student-registrations.create') }}">Start Student Registration</a>
                <a class="btn btn-light" href="#late-registration">{{ $secondaryButton }}</a>
            </div>
            <div class="hero-note">
                <span><strong>Late registration deadline:</strong> February 10, 2026, based on the provided announcement.</span>
                <span><strong>Completion rule:</strong> registration is complete only after the filled-out form and payment are received.</span>
            </div>
        </div>
        <aside class="poster-card" aria-label="Provided AP registration poster">
            <img src="{{ asset('images/ap-late-registration-2026.jpeg') }}" alt="2026 AP Exam Registration late registration announcement">
            <div class="poster-caption"><span>Provided announcement content</span><span class="status-pill">Late Registration</span></div>
        </aside>
    </div>
</header>

<section class="quick" aria-label="Quick registration facts">
    <div class="quick-grid">
        <div class="quick-item"><strong>Feb. 10</strong><span>Late registration deadline from poster</span></div>
        <div class="quick-item"><strong>Taipei</strong><span>Test-center registration support</span></div>
        <div class="quick-item"><strong>Form + Payment</strong><span>Both required before completion</span></div>
        <div class="quick-item"><strong>No Login</strong><span>Student can fill registration directly</span></div>
    </div>
</section>

<main class="wrap">
    <section id="overview">
        <div class="section-head">
            <div>
                <div class="eyebrow">Prepare. Achieve.</div>
                <h2>Trinity Scholar AP Registration Service</h2>
                <p>{{ $overview?->body ?: 'Trinity Scholar helps students register for AP exams with clear instructions, document collection, exam selection, payment tracking, and coordinator verification.' }}</p>
            </div>
        </div>
        <div class="grid-3">
            <article class="card">
                <img src="{{ asset($assetBase.'images/course/cs-img1.jpg') }}" alt="Student learning support">
                <div class="card-pad"><h3>Guided AP Registration</h3><p>Students submit personal data, passport information, AP subject choices, and accommodation requests in one structured flow.</p></div>
            </article>
            <article class="card">
                <img src="{{ asset($assetBase.'images/about/abt-right-thumb.jpg') }}" alt="Academic advising">
                <div class="card-pad"><h3>Coordinator Review</h3><p>The AP Coordinator can verify documents, review payment, track quota, and complete registrations from the admin dashboard.</p></div>
            </article>
            <article class="card">
                <img src="{{ asset($assetBase.'images/course/cs-img3.jpg') }}" alt="Exam preparation classroom">
                <div class="card-pad"><h3>Test Prep Background</h3><p>Trinity Scholar's broader work includes test prep, admissions support, English learning, and personalized lessons.</p></div>
            </article>
        </div>
    </section>

    <section id="late-registration" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">Announcement Content</div>
                <h2>2026 AP Late Registration Information</h2>
                <p>Content below is based on the poster shared by the client/team for this update.</p>
            </div>
        </div>
        <div class="info-strip">
            <div class="announcement">
                <p><strong>English:</strong> Trinity Scholar is now accepting AP Late Registrations on behalf of students until <strong>Feb. 10</strong> for the test center in Taipei.</p>
                <p><strong>Note:</strong> There will be an extra late registration fee. Registration may close earlier if all available seats are filled.</p>
                <p><strong>Completion:</strong> The registration is only considered completed when both the filled-out form and payment are received.</p>
            </div>
            <div class="announcement">
                <p><strong>中文:</strong> 力可即日起開始接受台北考場 AP 考試 Late Registration 報名代辦，至 <strong>2/10</strong> 止。</p>
                <p><strong>提醒:</strong> 台北考場 AP 考試 Late Registration 開放報名，會有額外的延遲報名費用，額滿為止。</p>
                <p><strong>已額滿:</strong> AP Chinese、AP Calculus、AP Macro/Micro 已額滿。</p>
            </div>
        </div>
    </section>

    <section id="timeline" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">{{ __('landing.current_status') }}</div>
                <h2>{{ __('landing.registration_timeline') }}</h2>
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

    <section id="process" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">{{ $process?->eyebrow ?: 'Registration Flow' }}</div>
                <h2>{{ $process?->title ?: 'How students register' }}</h2>
                <p>{{ $process?->body ?: 'A direct student form collects the required registration details without login.' }}</p>
            </div>
        </div>
        <div class="process">
            @foreach (($process?->items ?: ['Fill student form', 'Select AP exams', 'Upload passport', 'Submit payment proof']) as $item)
                <div class="process-item">{{ $item }}</div>
            @endforeach
        </div>
    </section>

    <section id="fees" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">Fees</div>
                <h2>{{ __('landing.exam_fee') }}</h2>
                <p>Late registration may include extra fees. Final amount is confirmed by selected exam subjects and coordinator settings.</p>
            </div>
        </div>
        <div class="grid-3">
            @foreach ($fees as $fee)
                <div class="card card-pad fee-card">
                    <div><h3>{{ $fee->name }}</h3><p>{{ $fee->description }}</p></div>
                    <div class="amount">{{ $fee->currency }} {{ number_format($fee->amount) }}</div>
                </div>
            @endforeach
            <div class="card card-pad fee-card total-card">
                <div><h3>Estimated Base Total</h3><p>Before subject-specific or late adjustments.</p></div>
                <div class="amount">NTD {{ number_format($feeTotal) }}</div>
            </div>
        </div>
    </section>

    <section id="documents" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">Checklist</div>
                <h2>{{ __('landing.required_documents') }}</h2>
            </div>
        </div>
        <div class="grid-3">
            @foreach ($documents as $document)
                <div class="card card-pad doc">
                    <span class="check" aria-hidden="true">OK</span>
                    <div><h3>{{ $document->name }}</h3><p>{{ $document->description }}</p></div>
                </div>
            @endforeach
        </div>
    </section>

    <section id="faq" style="margin-top:44px">
        <div class="section-head">
            <div>
                <div class="eyebrow">FAQ</div>
                <h2>{{ __('landing.frequently_asked_questions') }}</h2>
            </div>
        </div>
        @foreach ($faqs as $faq)
            <details>
                <summary>{{ $faq->question }}</summary>
                <p>{{ $faq->answer }}</p>
            </details>
        @endforeach
    </section>

    <section id="contact" class="grid-2" style="margin-top:44px">
        <div class="card card-pad">
            <div class="eyebrow">Contact</div>
            <h2>{{ __('landing.contact_information') }}</h2>
            <p><strong>Organization:</strong> {{ $contact?->organization ?: 'Trinity Scholar' }}</p>
            <p><strong>Email:</strong> {{ $contact?->email ?: 'info@trinityscholar.com' }}</p>
            <p><strong>Phone:</strong> {{ $contact?->phone ?: '886-2-2771-6002' }}</p>
            <p><strong>Office Hours:</strong> {{ $contact?->office_hours ?: 'Mon-Fri 9:00-18:00' }}</p>
        </div>
        <div class="card card-pad">
            <div class="eyebrow">{{ $privacy?->eyebrow ?: 'Privacy' }}</div>
            <h2>{{ $privacy?->title ?: 'Private documents stay protected' }}</h2>
            <p>{{ $privacy?->body ?: 'Passport and payment documents are stored privately and only available to authorized administrators.' }}</p>
            <ul>
                @foreach (($privacy?->items ?? ['Private passport upload', 'Admin-only document review', 'Audit logging']) as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="cta" aria-labelledby="cta-title">
        <div>
            <h2 id="cta-title">{{ __('landing.ready_headline') }}</h2>
            <p>{{ __('landing.ready_body') }}</p>
        </div>
        <a class="btn btn-gold" href="{{ route('student-registrations.create') }}">Open Registration Form</a>
    </section>
</main>

<footer class="footer">
    <div class="footer-inner">
        <span>Copyright 2026 Trinity Scholar AP Registration Platform</span>
        <span>Student registration without login - Admin verification - Payment tracking</span>
    </div>
</footer>
</body>
</html>
