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
    $brandLogo = 'images/trinity-scholar-logo.png';
    $brandFavicon = 'images/trinity-scholar-favicon.png';
    $uiLocale = session('locale', str_replace('_', '-', app()->getLocale()));
    $isZh = $uiLocale === 'zh-TW';
    $navLabels = $isZh
        ? ['home' => '首頁', 'program' => '課程資訊', 'timeline' => '時程', 'fees' => '費用', 'faq' => '常見問題', 'contact' => '聯絡我們', 'start' => '開始報名', 'support' => '台北 AP 報名支援']
        : ['home' => 'Home', 'program' => 'Program', 'timeline' => 'Timeline', 'fees' => 'Fees', 'faq' => 'FAQ', 'contact' => 'Contact', 'start' => 'Start Form', 'support' => 'Taipei AP Registration Support'];
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
    <script src="{{ asset($assetBase.'js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <style>
        :root{--trinity-blue:#244e9a;--trinity-blue-dark:#142f63;--trinity-blue-soft:#eaf2ff;--trinity-blue-bright:#9db9ff}
        html{scroll-behavior:smooth}
        .primary-color{color:var(--trinity-blue)!important}
        .primary-bg,.btn-primary,.media-head.primary-bg,.cs-price.primary-bg{background:var(--trinity-blue)!important;border-color:var(--trinity-blue)!important}
        .btn-primary:hover{background:var(--trinity-blue-dark)!important;border-color:var(--trinity-blue-dark)!important}
        .main-menu nav ul li a:before,.section-title-style2 span:before,.section-title-style2 span:after{background:var(--trinity-blue)!important}
        .header-top{background:var(--trinity-blue)!important}
        #header .header-bottom{background:rgba(15,18,24,.62)}
        #header .ht-social li{color:#fff;font-size:14px;font-weight:400;letter-spacing:0}
        #header .header-bottom-inner{min-height:104px}
        #header .logo a{display:inline-flex;align-items:center;background:#fff;border-radius:8px;padding:9px 14px;box-shadow:0 12px 28px rgba(0,0,0,.16)}
        #header .logo img{width:230px;max-height:64px;object-fit:contain}
        #header .main-menu{text-align:center}
        #header .main-menu nav ul li a{padding:43px 15px}
        #header .main-menu nav ul li.active a,#header .main-menu nav ul li a:hover{color:var(--trinity-blue-bright)}
        #header .public-header-actions{display:flex;align-items:center;justify-content:flex-end;gap:12px}
        #header .public-header-actions .btn{white-space:nowrap;padding:16px 25px}
        #header .public-header-actions .btn-primary{background:var(--trinity-blue)!important;border-color:var(--trinity-blue)!important;color:#fff!important}
        #header .public-header-actions .btn.btn-round{border-radius:50px!important;line-height:12px}
        #header .language-switcher{margin:0}
        #header .language-switcher label{display:block;margin:0}
        #header .language-switcher select{height:48px;min-width:126px;border:1px solid rgba(255,255,255,.55);border-radius:50px;background:rgba(255,255,255,.96);color:#252525;padding:0 18px;font-family:"Muli",sans-serif;font-size:14px;font-weight:700;text-transform:uppercase}
        #header .ht-social .language-switcher select{height:34px;min-width:105px;border:0}
        footer .widget-company img{background:#fff;border-radius:8px;padding:10px 14px;width:250px;max-width:100%;height:auto}
        footer .primary-color{color:var(--trinity-blue-bright)!important}
        footer .footer-bottom a{color:inherit;text-decoration:underline;text-underline-offset:2px}
        footer .footer-bottom a:hover{color:var(--trinity-blue-bright)}
        @media(max-width:1199px){#header .main-menu nav ul li a{padding:43px 10px}#header .public-header-actions .btn{padding:15px 18px}}
        @media(max-width:991px){#header .header-bottom{background:rgba(15,18,24,.86)}#header .header-bottom-inner{min-height:auto;padding:18px 0}#header .public-header-actions{justify-content:flex-start;margin-top:10px}.slicknav_btn{margin-top:-39px}}
        @media(max-width:575px){#header .header-top{display:none}#header .logo img{width:190px}#header .public-header-actions{gap:8px;flex-wrap:wrap}#header .language-switcher select{height:42px;min-width:104px}#header .public-header-actions .btn{padding:13px 16px}}
    </style>
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
                        <a href="{{ route('landing') }}"><img src="{{ asset($brandLogo) }}" alt="Trinity Scholar"></a>
                        <div class="address">
                            <h6>Office Address</h6>
                            <p>Taipei test-center AP registration support.</p>
                        </div>
                        <div class="address">
                            <h6>Business Phone</h6>
                            <p>886-2-2771-6002</p>
                        </div>
                        <div class="address">
                            <h6>Business Email</h6>
                            <p>info@trinityscholar.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="widget footer-link">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">Registration</h4>
                        <ul>
                            <li><a href="{{ route('landing') }}#overview"><i class="fa fa-angle-right"></i>Program Information</a></li>
                            <li><a href="{{ route('landing') }}#timeline"><i class="fa fa-angle-right"></i>Timeline</a></li>
                            <li><a href="{{ route('landing') }}#fees"><i class="fa fa-angle-right"></i>Fees</a></li>
                            <li><a href="{{ route('student-registrations.create') }}"><i class="fa fa-angle-right"></i>Register Now</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="widget widget-opening">
                        <h4 class="fwidget-title mb-5 pb-3 primary-color">Important Notice</h4>
                        <p>Registration is complete only after the filled-out form and payment are received. Available seats may close before the listed deadline.</p>
                        <ul>
                            <li><span>Main Period :</span>August - October</li>
                            <li><span>Late Period :</span>January - March</li>
                            <li><span>Deadline :</span>February 10, 2026 for the current late-registration notice</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>Copyright &copy; 2026 Trinity Scholar. All Rights Reserved. Designed By <a href="https://devhouse.sophistec.global/" target="_blank" rel="noopener">Sophistec Dev House</a>. Powered by <a href="https://sophistec.global/" target="_blank" rel="noopener">Sophistec Global</a>.</p>
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
</script>
{{ $scripts ?? '' }}
@stack('scripts')
</body>
</html>
