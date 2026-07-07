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
@endphp
<!doctype html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $description }}">
    <title>{{ $title }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.ico') }}">
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
        #header .header-bottom-right-style-2{margin-left:0}
        #header .header-bottom-right-style-2 ul{display:flex;align-items:center;justify-content:flex-end;gap:10px;margin:0;padding:0}
        #header .header-bottom-right-style-2 li{display:inline-flex;align-items:center}
        #header .header-bottom-right-style-2 li a.btn{white-space:nowrap}
        #header .language-switcher{margin:0}
        #header .language-switcher label{display:block;margin:0}
        #header .language-switcher select{height:47px;min-width:116px;border:1px solid #ccc;border-radius:50px;background:#fff;color:#252525;padding:0 18px;font-family:"Muli",sans-serif;font-size:14px;font-weight:700;text-transform:uppercase}
        @media(max-width:991px){#header .header-bottom-right-style-2 ul{justify-content:flex-start;margin:12px 0}}
    </style>
    {{ $styles ?? '' }}
    @stack('styles')
</head>
<body class="trinity-public {{ $bodyClass }}">
<header id="header">
    <div class="header-two">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-sm-6 d-block d-lg-none">
                    <div class="logo">
                        <a href="{{ route('landing') }}"><img src="{{ asset($assetBase.'images/icon/logo.png') }}" alt="Trinity Scholar"></a>
                    </div>
                </div>
                <div class="col-lg-8 offset-lg-1 d-none d-lg-block">
                    <div class="main-menu menu-style2">
                        <nav>
                            <ul id="m_menu_active">
                                <li class="{{ request()->routeIs('landing') ? 'active' : '' }}"><a href="{{ route('landing') }}">Home</a></li>
                                <li><a href="{{ route('landing') }}#overview">Program</a></li>
                                <li><a href="{{ route('landing') }}#timeline">Timeline</a></li>
                                <li class="middle-logo">
                                    <a href="{{ route('landing') }}">
                                        <img src="{{ asset($assetBase.'images/icon/logo-middle.png') }}" alt="Trinity Scholar">
                                        <img class="hb-bottom-shape" src="{{ asset($assetBase.'images/icon/hb-bottom-shape.png') }}" alt="">
                                    </a>
                                </li>
                                <li><a href="{{ route('landing') }}#fees">Fees</a></li>
                                <li><a href="{{ route('landing') }}#faq">FAQ</a></li>
                                <li><a href="{{ route('landing') }}#contact">Contact</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-5">
                    <div class="header-bottom-right-style-2">
                        <ul>
                            <li><x-language-switcher /></li>
                            <li><a class="btn btn-primary btn-round {{ request()->routeIs('student-registrations.create') ? 'active' : '' }}" href="{{ route('student-registrations.create') }}">Start Form</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 d-block d-lg-none">
                    <div id="mobile_menu"></div>
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
                        <a href="{{ route('landing') }}"><img src="{{ asset($assetBase.'images/icon/logo.png') }}" alt="Trinity Scholar"></a>
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
                <p>Copyright &copy; 2026 <span><a class="primary-color" href="{{ route('landing') }}">Trinity Scholar</a></span> - AP Exam Registration Platform.</p>
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
{{ $scripts ?? '' }}
@stack('scripts')
</body>
</html>
