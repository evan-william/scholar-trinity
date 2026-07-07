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
        :root{--ts-primary:#fc9928;--ts-dark:#14171d;--ts-blue:#1e2c39;--ts-muted:#7d7d7d;--ts-border:#e6e9f2;--ts-soft:#f6f7fb;--ts-success:#237a4f;--ts-danger:#b42318}
        html{scroll-behavior:smooth}
        body.trinity-public{background:#fff;color:var(--ts-muted)}
        body.trinity-public .header-two{background:#212121}
        body.trinity-public .main-menu nav ul li a{padding:43px 15px}
        body.trinity-public .middle-logo img:first-child{max-width:118px}
        body.trinity-public .header-bottom-right-style-2 ul{margin:0;padding:0;display:flex;gap:10px;align-items:center;justify-content:flex-end}
        body.trinity-public .header-bottom-right-style-2 li{display:inline-block}
        body.trinity-public .header-bottom-right-style-2 .btn{padding:13px 18px}
        body.trinity-public .language-switcher label{margin:0}
        body.trinity-public .language-switcher select{height:42px;border:1px solid #efefef;border-radius:50px;padding:0 13px;background:#fff;color:#252525;font-size:12px;font-weight:700;text-transform:uppercase}
        body.trinity-public .hero-area{background:url('{{ asset($assetBase.'images/bg/hero-bg.jpg') }}') center/cover no-repeat;position:relative;z-index:1}
        body.trinity-public .hero-area:before{content:"";position:absolute;inset:0;background:#14171d;opacity:.74;z-index:-1}
        body.trinity-public .hero-content h1{font-size:58px;line-height:70px}
        body.trinity-public .hero-content p{font-size:21px;line-height:34px}
        body.trinity-public .trinity-content{background:#fff}
        body.trinity-public .trinity-card{border:1px solid var(--ts-border);background:#fff;transition:all .3s ease}
        body.trinity-public .trinity-card:hover{box-shadow:0 -6px 24px rgba(10,10,10,.09)}
        body.trinity-public .trinity-card-body{padding:25px}
        body.trinity-public .trinity-card h3,
        body.trinity-public .trinity-card h4{letter-spacing:0}
        body.trinity-public .trinity-meta{font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--ts-primary);margin-bottom:8px;display:block}
        body.trinity-public .summary-table{width:100%;border-collapse:collapse}
        body.trinity-public .summary-table td{border-bottom:1px solid #ebebeb;padding:11px 0;vertical-align:top}
        body.trinity-public .summary-table td:first-child{width:40%;font-weight:700;color:#252525;padding-right:18px}
        body.trinity-public .status{display:inline-block;border-radius:50px;padding:5px 11px;background:#f6f7fb;color:#252525;font-size:12px;font-weight:700;text-transform:capitalize}
        body.trinity-public .status.paid,
        body.trinity-public .status.completed,
        body.trinity-public .status.issued,
        body.trinity-public .status.sent{background:#e8f6ef;color:var(--ts-success)}
        body.trinity-public .status.failed,
        body.trinity-public .status.rejected,
        body.trinity-public .status.cancelled{background:#fff0ee;color:var(--ts-danger)}
        body.trinity-public .amount{font-size:24px;font-weight:700;color:#252525}
        body.trinity-public .steps{padding-left:20px}
        body.trinity-public .steps li{margin-bottom:8px}
        body.trinity-public .notice{border-left:4px solid var(--ts-primary);background:#fff8ed;padding:16px 18px;margin-bottom:18px;color:#6f4a11}
        body.trinity-public .notice.success{border-color:var(--ts-success);background:#e8f6ef;color:var(--ts-success)}
        body.trinity-public .notice.error{border-color:var(--ts-danger);background:#fff0ee;color:var(--ts-danger)}
        body.trinity-public .footer-top{padding-top:90px}
        body.trinity-public .footer-bottom{margin-top:55px}
        body.trinity-public .footer-top .widget p,
        body.trinity-public .footer-top .widget li{color:#b7bdca}
        body.trinity-public .footer-top .widget a{color:#edf1ff}
        @media(max-width:991px){
            body.trinity-public .header-two{padding-top:20px}
            body.trinity-public .hero-area{margin-top:0}
            body.trinity-public .hero-content h1{font-size:40px;line-height:52px}
        }
        @media(max-width:767px){
            body.trinity-public .header-bottom-right-style-2 ul{justify-content:flex-start;margin:10px 0}
            body.trinity-public .hero-content h1{font-size:31px;line-height:42px}
            body.trinity-public .hero-content p{font-size:16px;line-height:28px}
            body.trinity-public .summary-table td{display:block;width:100%;padding:8px 0}
            body.trinity-public .summary-table td:first-child{width:100%;border-bottom:0;padding-bottom:0}
        }
    </style>
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
                <div class="col-lg-9 offset-lg-1 d-none d-lg-block">
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
                <div class="col-lg-2 col-sm-5">
                    <div class="header-bottom-right-style-2">
                        <ul>
                            <li><x-language-switcher /></li>
                            <li><a class="btn btn-primary btn-round" href="{{ route('student-registrations.create') }}">Register</a></li>
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

<main class="trinity-content {{ $contentClass }}">
    @if(session('status'))
        <div class="container"><div class="notice success">{{ session('status') }}</div></div>
    @endif
    @if($errors->any())
        <div class="container"><div class="notice error">{{ $errors->first() }}</div></div>
    @endif
    {{ $slot }}
</main>

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
@stack('scripts')
</body>
</html>
