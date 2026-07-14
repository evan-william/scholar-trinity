@props([
    'title' => 'Trinity Scholar',
    'description' => 'Trinity Scholar AP Exam registration support in Taipei.',
    'eyebrow' => 'AP Exam Registration',
    'heading' => null,
    'subtitle' => null,
    'badge' => null,
    'bodyClass' => '',
    'contentClass' => 'ptb--120',
])
@php
    $assetBase = 'theme/edification/';
    $brandLogo = 'images/trinity-scholar-logo-clean.png';
    $footerLogo = 'images/trinity-scholar-logo-clean.png';
    $brandFavicon = 'images/trinity-scholar-favicon.png';
    $publicUiVersion = '20260714-2';
    $uiLocale = session('locale', str_replace('_', '-', app()->getLocale()));
    $isZh = $uiLocale === 'zh-TW';
    $navLabels = $isZh
        ? ['home' => '首頁', 'program' => '課程資訊', 'timeline' => '時程', 'fees' => '費用', 'faq' => '常見問題', 'contact' => '聯絡我們', 'start' => '開始報名', 'support' => '台北 AP 報名支援']
        : ['home' => 'Home', 'program' => 'Program', 'timeline' => 'Timeline', 'fees' => 'Fees', 'faq' => 'FAQ', 'contact' => 'Contact', 'start' => 'Start Form', 'support' => 'Taipei AP Registration Support'];
    $footerLabels = $isZh
        ? [
            'office' => '服務說明',
            'office_body' => '台北考場 AP 報名支援。',
            'phone' => '聯絡電話',
            'email' => '電子郵件',
            'registration' => '報名資訊',
            'program' => '課程資訊',
            'timeline' => '報名時程',
            'fees' => '費用說明',
            'register' => '立即報名',
            'notice' => '重要提醒',
            'notice_body' => '報名需在表單與付款皆收到後才算完成。名額有限，可能在公告截止日前額滿關閉。',
            'main_period' => '一般時段：',
            'late_period' => '逾期時段：',
            'deadline' => '截止日期：',
            'main_period_value' => '八月至十月',
            'late_period_value' => '一月至三月',
            'deadline_value' => '本次逾期報名公告為 2026 年 2 月 10 日',
            'copyright' => '版權所有',
            'rights' => '保留所有權利。',
            'designed' => 'Designed By',
            'powered' => 'Powered by',
        ]
        : [
            'office' => 'Office Address',
            'office_body' => 'Taipei test-center AP registration support.',
            'phone' => 'Business Phone',
            'email' => 'Business Email',
            'registration' => 'Registration',
            'program' => 'Program Information',
            'timeline' => 'Timeline',
            'fees' => 'Fees',
            'register' => 'Register Now',
            'notice' => 'Important Notice',
            'notice_body' => 'Registration is complete only after the filled-out form and payment are received. Available seats may close before the listed deadline.',
            'main_period' => 'Main Period :',
            'late_period' => 'Late Period :',
            'deadline' => 'Deadline :',
            'main_period_value' => 'August - October',
            'late_period_value' => 'January - March',
            'deadline_value' => 'February 10, 2026 for the current late-registration notice',
            'copyright' => 'Copyright',
            'rights' => 'All Rights Reserved.',
            'designed' => 'Designed By',
            'powered' => 'Powered by',
        ];
@endphp
<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/png" href="{{ asset($brandFavicon) }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/typography.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/default-css.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset($assetBase.'css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/trinity/css/public-ui.css') }}?v={{ $publicUiVersion }}">
    <style>
        /* Critical brand rules keep the public shell correct while deployed caches refresh. */
        body.trinity-public #header .header-top,
        body.trinity-public .primary-bg,
        body.trinity-public .btn-primary,
        body.trinity-public .media-head.primary-bg,
        body.trinity-public .cs-price.primary-bg {
            color: #fff !important;
            background: #244e9a !important;
            border-color: #244e9a !important;
        }
        body.trinity-public .primary-color { color: #244e9a !important; }
        body.trinity-public #header .main-menu nav ul li a:before,
        body.trinity-public .slider-content h3:before,
        body.trinity-public .section-title-style2 span:before,
        body.trinity-public .section-title-style2 span:after { background: #244e9a !important; }
        body.trinity-public #header .ht-address li,
        body.trinity-public #header .ht-social li { color: rgba(255,255,255,.94) !important; }
        body.trinity-public #header .logo img {
            width: 180px;
            height: 82px;
            max-height: 82px;
            object-fit: contain;
            filter: none !important;
        }
        body.trinity-public #header .public-header-actions {
            display: flex !important;
            flex-flow: row nowrap !important;
            align-items: center !important;
            justify-content: flex-end !important;
            gap: 10px !important;
        }
        body.trinity-public #header .language-switcher,
        body.trinity-public #header .language-switcher label { display: block; flex: 0 0 auto; margin: 0 !important; }
        body.trinity-public #header .language-switcher select { min-width: 104px; height: 44px; border-radius: 50px; }
        body.trinity-public #header .public-header-actions .btn { flex: 0 0 auto; padding: 14px 18px; white-space: nowrap; }
        body.trinity-public footer .widget-company img {
            width: 176px;
            height: 100px;
            object-fit: contain;
            filter: none !important;
        }
        @media (max-width: 575px) {
            body.trinity-public #header .logo img { width: 138px; height: 68px; }
            body.trinity-public #header .public-header-actions { justify-content: flex-start !important; }
            body.trinity-public #header .language-switcher select { min-width: 96px; height: 40px; }
            body.trinity-public #header .public-header-actions .btn { padding: 12px 14px; font-size: 12px; }
        }
    </style>
    <script src="{{ asset($assetBase.'js/vendor/modernizr-2.8.3.min.js') }}"></script>
    {{ $styles ?? '' }}
    @stack('styles')
</head>
<body class="trinity-public {{ $bodyClass }}">
<header id="header">
    <div class="header-top">
        <div class="container">
            <div class="row d-flex flex-center">
                <div class="col-sm-8">
                    <div class="ht-address">
                        <ul>
                            <li><i class="fa fa-phone"></i>886-2-2771-6002</li>
                            <li><i class="fa fa-envelope"></i>info@trinityscholar.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="ht-social">
                        <ul>
                            <li>{{ $navLabels['support'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-inner">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-sm-8">
                        <div class="logo">
                            <a href="{{ route('landing') }}"><img src="{{ asset($brandLogo) }}" alt="Trinity Scholar"></a>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 d-none d-lg-block">
                        <div class="main-menu">
                            <nav>
                                <ul id="m_menu_active">
                                    <li class="{{ request()->routeIs('landing') ? 'active' : '' }}"><a href="{{ route('landing') }}">{{ $navLabels['home'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#overview">{{ $navLabels['program'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#timeline">{{ $navLabels['timeline'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#fees">{{ $navLabels['fees'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#faq">{{ $navLabels['faq'] }}</a></li>
                                    <li><a href="{{ route('landing') }}#contact">{{ $navLabels['contact'] }}</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-4">
                        <div class="public-header-actions">
                            <x-language-switcher />
                            <a class="btn btn-primary btn-round {{ request()->routeIs('student-registrations.create') ? 'active' : '' }}" href="{{ route('student-registrations.create') }}">{{ $navLabels['start'] }}</a>
                        </div>
                    </div>
                    <div class="col-12 d-block d-lg-none">
                        <div id="mobile_menu"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

@if(isset($hero) && trim((string) $hero) !== '')
    {{ $hero }}
@elseif($heading)
    <div class="crumbs-area">
        <div class="container">
            <div class="crumb-content">
                <h4><span>{{ $eyebrow }}</span></h4>
                <p class="crumb-title">{{ $heading }}</p>
                @if($subtitle)
                    <p class="text-white mt-4 mb-0" style="max-width:760px">{{ $subtitle }}</p>
                @endif
                @if($badge)
                    <span class="btn btn-primary btn-round mt-4">{{ $badge }}</span>
                @endif
            </div>
        </div>
    </div>
@endif
{{ $progress ?? '' }}

@if($contentClass === 'none')
    {{ $slot }}
@else
    <main class="{{ $contentClass }}">
        @if(session('status'))
            <div class="container"><div class="alert alert-success">{{ session('status') }}</div></div>
        @endif
        @if($errors->any())
            <div class="container"><div class="alert alert-danger">{{ $errors->first() }}</div></div>
        @endif
        <div class="container">
            {{ $slot }}
        </div>
    </main>
@endif

<footer>
    <div class="footer-top has-color pt--120 pb--30">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="widget widget-company">
                        <a href="{{ route('landing') }}"><img src="{{ asset($footerLogo) }}" alt="Trinity Scholar"></a>
                        <div class="address">
                            <h6>{{ $footerLabels['office'] }}</h6>
                            <p>{{ $footerLabels['office_body'] }}</p>
                        </div>
                        <div class="address">
                            <h6>{{ $footerLabels['phone'] }}</h6>
                            <p>886-2-2771-6002</p>
                        </div>
                        <div class="address">
                            <h6>{{ $footerLabels['email'] }}</h6>
                            <p>info@trinityscholar.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget footer-link">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">{{ $footerLabels['registration'] }}</h4>
                        <ul>
                            <li><a href="{{ route('landing') }}#overview"><i class="fa fa-angle-right"></i>{{ $footerLabels['program'] }}</a></li>
                            <li><a href="{{ route('landing') }}#timeline"><i class="fa fa-angle-right"></i>{{ $footerLabels['timeline'] }}</a></li>
                            <li><a href="{{ route('landing') }}#fees"><i class="fa fa-angle-right"></i>{{ $footerLabels['fees'] }}</a></li>
                            <li><a href="{{ route('student-registrations.create') }}"><i class="fa fa-angle-right"></i>{{ $footerLabels['register'] }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="widget widget-opening">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">{{ $footerLabels['notice'] }}</h4>
                        <p>{{ $footerLabels['notice_body'] }}</p>
                        <ul>
                            <li><span>{{ $footerLabels['main_period'] }}</span>{{ $footerLabels['main_period_value'] }}</li>
                            <li><span>{{ $footerLabels['late_period'] }}</span>{{ $footerLabels['late_period_value'] }}</li>
                            <li><span>{{ $footerLabels['deadline'] }}</span>{{ $footerLabels['deadline_value'] }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>{{ $footerLabels['copyright'] }} &copy; 2026 Trinity Scholar. {{ $footerLabels['rights'] }} {{ $footerLabels['designed'] }} <a href="https://devhouse.sophistec.global/" target="_blank" rel="noopener">Sophistec Dev House</a>. {{ $footerLabels['powered'] }} <a href="https://sophistec.global/" target="_blank" rel="noopener">Sophistec Global</a>.</p>
            </div>
        </div>
    </div>
</footer>

<script src="{{ asset($assetBase.'js/vendor/jquery-2.2.4.min.js') }}"></script>
<script src="{{ asset($assetBase.'js/bootstrap.min.js') }}"></script>
<script src="{{ asset($assetBase.'js/owl.carousel.min.js') }}"></script>
<script src="{{ asset($assetBase.'js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset($assetBase.'js/jquery.slicknav.min.js') }}"></script>
<script src="{{ asset($assetBase.'js/plugins.js') }}"></script>
<script src="{{ asset($assetBase.'js/scripts.js') }}"></script>
<script>
    document.addEventListener('click', function (event) {
        const link = event.target.closest('a[href*="#"]');
        if (!link) return;

        const url = new URL(link.href, window.location.href);
        if (url.pathname !== window.location.pathname || !url.hash) return;

        const target = document.getElementById(url.hash.slice(1));
        if (!target) return;

        event.preventDefault();
        history.pushState(null, '', url.hash);
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const targets = document.querySelectorAll('.landing-refined section, .landing-refined .visual-card, .landing-refined .media, .landing-refined .process-item');
        if (reduceMotion || !('IntersectionObserver' in window)) {
            targets.forEach((target) => target.classList.add('is-visible'));
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

        targets.forEach((target) => {
            target.classList.add('reveal-on-scroll');
            observer.observe(target);
        });
    })();
</script>
{{ $scripts ?? '' }}
@stack('scripts')
</body>
</html>
