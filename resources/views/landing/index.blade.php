@php
    $uiLocale = session('locale', str_replace('_', '-', app()->getLocale()));
    $isZh = $uiLocale === 'zh-TW';
    $tx = fn (string $en, string $zh): string => $isZh ? $zh : $en;
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $metaTitle = data_get($seo, 'meta_title.text', $tx('2026 AP Exam Registration | Trinity Scholar', '2026 AP 考試報名 | Trinity Scholar'));
    $metaDescription = data_get($seo, 'meta_description.text', $tx('Trinity Scholar AP Exam registration service for students in Taipei.', 'Trinity Scholar 提供台北學生 AP 考試報名支援服務。'));
    $heroTitle = data_get($hero, 'title.text', $tx('2026 Advanced Placement (AP) Exam Registration', '2026 Advanced Placement (AP) 考試報名'));
    $heroIntro = data_get($hero, 'introduction.text', $tx('Trinity Scholar offers hassle-free AP Exam registration service for students who need test-center registration support in Taipei.', 'Trinity Scholar 為需要台北考場報名支援的學生提供 AP 考試報名服務。'));
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
    $assetBase = 'theme/edification/';
    $displayFees = $fees->isNotEmpty() ? $fees : collect([
        (object) [
            'currency' => 'NTD',
            'name' => $tx('AP Exam Fee', 'AP 考試費'),
            'description' => $tx('Collected for official AP exam registration. Final subject pricing is confirmed by the admin team after review.', '用於正式 AP 考試報名，最終科目費用由管理團隊審核後確認。'),
            'amount' => 7800,
        ],
        (object) [
            'currency' => 'NTD',
            'name' => $tx('Trinity Service Fee', 'Trinity 服務費'),
            'description' => $tx('Service handling fee for registration coordination, document review, payment checking, and student follow-up.', '包含報名協調、文件審核、付款確認與學生後續聯繫的服務費。'),
            'amount' => 1200,
        ],
        (object) [
            'currency' => 'NTD',
            'name' => $tx('Late Registration Fee', '逾期報名費'),
            'description' => $tx('Applied during the late-registration period. Seats are limited and may close before the listed deadline.', '逾期報名期間適用。名額有限，可能在公告截止日前額滿關閉。'),
            'amount' => 1500,
        ],
    ]);
    $displayDocuments = $documents->isNotEmpty() ? $documents : collect([
        (object) ['name' => $tx('Passport', '護照'), 'description' => $tx('Clear passport photo page or PDF upload is required for exam registration verification.', '需上傳清楚的護照照片頁或 PDF，以便進行考試報名資料核對。')],
        (object) ['name' => $tx('Student Information', '學生資料'), 'description' => $tx('Legal English name, school, grade, student email, phone, nationality, and date of birth.', '英文法定姓名、學校、年級、學生 Email、電話、國籍與出生日期。')],
        (object) ['name' => $tx('Parent Information', '家長資料'), 'description' => $tx('Parent or guardian name, relationship, email, phone, mailing address, city, and postal code.', '家長或監護人姓名、關係、Email、電話、通訊地址、城市與郵遞區號。')],
        (object) ['name' => $tx('AP Exam Selection', 'AP 考試選擇'), 'description' => $tx('Selected AP subjects, late-registration status, and any practice exam or preparation interest.', '選擇 AP 科目、逾期報名狀態，以及模擬考或備考課程意願。')],
        (object) ['name' => $tx('Payment Proof', '付款證明'), 'description' => $tx('Payment must be submitted and verified before the registration can be marked completed.', '付款需提交並完成審核後，報名才可標記為完成。')],
        (object) ['name' => $tx('Accommodation Documents', '特殊需求文件'), 'description' => $tx('Required only when requesting College Board approved accommodations or SSD support.', '僅在申請 College Board 已核准特殊需求或 SSD 支援時需要提供。')],
    ]);
    $displayFaqs = $faqs->isNotEmpty() ? $faqs : collect([
        (object) [
            'question' => $tx('What is AP?', '什麼是 AP？'),
            'answer' => $tx('AP stands for Advanced Placement. AP exams allow students to demonstrate college-level subject knowledge and may support university applications.', 'AP 是 Advanced Placement，讓學生展現大學程度的學科能力，並可作為大學申請資料的一部分。'),
        ],
        (object) [
            'question' => $tx('Who can register through Trinity Scholar?', '誰可以透過 Trinity Scholar 報名？'),
            'answer' => $tx('Students who need Taipei test-center registration support can submit the student registration form without logging in first.', '需要台北考場報名支援的學生，可不需登入直接填寫學生報名表。'),
        ],
        (object) [
            'question' => $tx('When is the late-registration deadline?', '逾期報名截止日是什麼時候？'),
            'answer' => $tx('For the current 2026 late-registration notice, the deadline is February 10, 2026. Registration may close earlier if available seats are filled.', '本次 2026 逾期報名公告截止日為 2026 年 2 月 10 日；若名額額滿，可能提前關閉報名。'),
        ],
        (object) [
            'question' => $tx('When is registration considered complete?', '什麼時候才算完成報名？'),
            'answer' => $tx('Registration is complete only after the filled-out form and payment are received, then reviewed by the admin team.', '需提交完整表單並完成付款，經管理團隊審核後才算完成報名。'),
        ],
        (object) [
            'question' => $tx('Can I change my exam selection?', '可以更改考試科目嗎？'),
            'answer' => $tx('Changes depend on deadline, subject availability, quota, and coordinator approval. Students should contact the admin team as early as possible.', '是否可更改取決於截止日期、科目名額、考場配額與協調員審核，請盡早聯繫管理團隊。'),
        ],
        (object) [
            'question' => $tx('Can I request accommodations?', '可以申請特殊考試需求嗎？'),
            'answer' => $tx('Yes. Students can mark accommodation needs and provide SSD or supporting documentation during registration when applicable.', '可以。若適用，學生可在報名時標記特殊需求並提供 SSD 或相關證明文件。'),
        ],
    ]);
    $feeTotal = $displayFees->sum('amount');
@endphp

<x-public-flow-shell :title="$metaTitle" :description="$metaDescription" body-class="landing-premium" content-class="none">
    <x-slot:styles>
        <style>
            #late-registration,
            #timeline,
            #process,
            #fees,
            #documents,
            #faq,
            #contact{scroll-margin-top:130px}
            .late-notice-area{background:#14171d;padding:56px 0 48px;position:relative;overflow:hidden}
            .late-notice-area:before{content:"";position:absolute;right:-120px;top:-120px;width:340px;height:340px;border:55px solid rgba(36,78,154,.14);border-radius:50%}
            .late-notice-area:after{content:"";position:absolute;left:-90px;bottom:-110px;width:260px;height:260px;background:rgba(255,255,255,.035);border-radius:50%}
            .late-notice-area .container{position:relative;z-index:1}
            .notice-card{height:100%;background:#fff;border:0!important;border-radius:4px;box-shadow:0 18px 45px rgba(0,0,0,.18)}
            .notice-card .card-body{padding:30px}
            .notice-kicker{display:inline-block;color:var(--trinity-blue);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px}
            .notice-list{margin:18px 0 0;padding:0}
            .notice-list li{display:flex;gap:10px;font-size:14px;font-weight:700;line-height:1.55;padding:5px 0;color:#5e6573}
            .notice-list li i{color:var(--trinity-blue);margin-top:4px}
            .late-stat{display:flex;align-items:center;gap:18px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);padding:18px 20px;margin-top:24px}
            .late-stat strong{display:block;color:#fff;font-size:28px;line-height:1;font-family:"Roboto Slab",serif}
            .late-stat span{display:block;color:rgba(255,255,255,.75);font-size:13px;text-transform:uppercase;letter-spacing:.06em}
            .late-stat i{display:grid;place-items:center;width:48px;height:48px;border-radius:50%;background:var(--trinity-blue);color:#fff;font-size:20px}
            .compact-section-title{margin-bottom:34px}
            .faq-card .card-body p{margin-bottom:0}
            .faq-list{max-width:920px;margin:0 auto;border-top:1px solid rgba(36,78,154,.14)}
            .faq-item{border-bottom:1px solid rgba(36,78,154,.14);background:#fff}
            .faq-item summary{position:relative;display:flex;align-items:center;min-height:74px;padding:20px 54px 20px 18px;color:#172033;font-family:"Roboto Slab","Microsoft JhengHei","PingFang TC",serif;font-size:18px;font-weight:700;cursor:pointer;list-style:none}
            .faq-item summary::-webkit-details-marker{display:none}
            .faq-item summary:after{content:"+";position:absolute;right:16px;display:grid;width:34px;height:34px;place-items:center;border:1px solid rgba(36,78,154,.18);border-radius:50%;background:#eaf2ff;color:var(--trinity-blue);font-family:"Muli",sans-serif;font-size:21px;font-weight:400;transition:transform 180ms var(--trinity-ease),background-color 180ms ease,color 180ms ease}
            .faq-item[open] summary:after{transform:rotate(45deg);background:var(--trinity-blue);color:#fff}
            .faq-item p{margin:0;padding:0 54px 24px 18px;color:#667386;font-size:14px;line-height:1.8;animation:faq-copy-in 180ms var(--trinity-ease) both}
            @keyframes faq-copy-in{from{opacity:0;transform:translate3d(0,-4px,0)}to{opacity:1;transform:translate3d(0,0,0)}}
            .section-title-style2{margin-bottom:36px;padding-top:18px}
            .section-title-style2 span{font-size:15px;letter-spacing:2px;color:#1e2c39}
            .section-title-style2 h2{font-size:42px;line-height:52px}
            .white-title span{color:rgba(255,255,255,.82)}
            .landing-card{border:1px solid #e5eaf2;box-shadow:0 14px 34px rgba(18,43,82,.07);transition:transform .2s ease,box-shadow .2s ease}
            .landing-card:hover{transform:translateY(-4px);box-shadow:0 22px 44px rgba(18,43,82,.11)}
            .course-thumb img{height:190px;width:100%;object-fit:cover}
            #overview{padding-top:58px!important;padding-bottom:74px!important}
            #timeline{padding-top:58px!important;padding-bottom:56px!important}
            #process{background:#12171f;padding-top:68px!important;padding-bottom:76px!important;overflow:hidden}
            #process:before{display:none!important}
            #process .container{position:relative;z-index:1}
            #process .section-title-style2{margin-bottom:34px;padding-top:0}
            #process .section-title-style2 span{color:rgba(255,255,255,.78)}
            #process .section-title-style2 h2{color:#fff!important}
            #process .section-title-style2 p{max-width:720px;margin:12px auto 0;color:rgba(255,255,255,.72)}
            #process .process-grid{justify-content:center}
            #process .process-col{display:flex}
            #process .process-card{width:100%;min-height:220px;border:1px solid rgba(255,255,255,.12);border-radius:4px;box-shadow:0 18px 45px rgba(0,0,0,.18);overflow:hidden}
            #process .process-card .teacher-content{padding:30px 24px!important}
            #process .process-step{display:inline-flex;align-items:center;justify-content:center;min-width:72px;height:30px;margin-bottom:18px;border-radius:50px;background:var(--trinity-blue-soft);color:var(--trinity-blue);font-family:"Muli",sans-serif;font-size:13px;font-weight:700;letter-spacing:0}
            #process .process-card h4{font-size:20px;line-height:30px;margin-bottom:10px!important;color:#1e2c39}
            #process .process-card p{margin:0;color:#737f8d;line-height:28px}
            #fees{padding-top:56px!important;padding-bottom:34px!important}
            #documents{padding-top:22px!important;padding-bottom:40px!important}
            #faq{padding-top:40px!important;padding-bottom:70px!important}
            #contact{padding-top:82px!important;padding-bottom:74px!important}
            .quick-facts{padding-top:56px!important;padding-bottom:18px!important}
            .quick-facts .card h3{font-size:28px;line-height:36px;margin-bottom:4px}
            .quick-facts .card p{margin-bottom:0}
            .cta-area{background:#121820!important}
            body.landing-premium{background:#f7faff;color:#526071}
            body.landing-premium .slider_item{position:relative;min-height:820px;overflow:hidden}
            body.landing-premium .slider_item:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(4,10,22,.94) 0%,rgba(8,18,36,.82) 36%,rgba(12,31,63,.34) 68%,rgba(8,18,36,.5) 100%);z-index:0}
            body.landing-premium .slider_item:after{content:"";position:absolute;left:-16vw;top:12%;width:64vw;height:64vw;border-radius:50%;background:radial-gradient(circle,rgba(36,78,154,.38),rgba(36,78,154,.06) 44%,rgba(36,78,154,0) 66%);z-index:0;pointer-events:none}
            body.landing-premium .slider_item .container{position:relative;z-index:1}
            body.landing-premium .slider-content{max-width:760px;animation:hero-copy-in 720ms var(--trinity-ease) both}
            body.landing-premium .slider-content h3{font-family:"Muli",sans-serif;font-size:20px;font-weight:500;letter-spacing:3.5px;color:rgba(255,255,255,.84);margin-bottom:18px}
            body.landing-premium .slider-content h1{font-weight:700;letter-spacing:-.01em;text-transform:none;font-size:66px;line-height:76px;max-width:13ch;text-wrap:balance;text-shadow:0 18px 54px rgba(0,0,0,.38)}
            body.landing-premium .slider-content h1 span{color:#8fb4ff!important}
            body.landing-premium .slider-content p{max-width:58ch;font-size:18px;line-height:32px;font-style:normal;color:rgba(255,255,255,.76);margin-bottom:0;text-wrap:pretty}
            body.landing-premium .slider-content .btn{margin-top:34px!important;background:#fff!important;border-color:#fff!important;color:#17366f!important;box-shadow:0 18px 46px rgba(0,0,0,.28)}
            body.landing-premium .slider-content .btn:hover{background:#eaf2ff!important;border-color:#eaf2ff!important;color:#10295a!important}
            @keyframes hero-copy-in{from{opacity:0;transform:translate3d(0,18px,0)}to{opacity:1;transform:translate3d(0,0,0)}}
            body.landing-premium .quick-facts{position:relative;margin-top:-72px;z-index:2;padding-top:0!important}
            body.landing-premium .quick-facts .container{background:rgba(255,255,255,.92);border:1px solid rgba(36,78,154,.13);border-radius:18px;padding:24px 28px;box-shadow:0 26px 70px rgba(18,43,82,.16);backdrop-filter:blur(14px)}
            body.landing-premium .quick-facts .landing-card{border:0!important;box-shadow:none!important;background:transparent}
            body.landing-premium .quick-facts .card-body{padding:8px 20px!important;border-left:1px solid rgba(36,78,154,.12)}
            body.landing-premium .quick-facts .col-md-3:first-child .card-body{border-left:0}
            body.landing-premium .quick-facts h3{font-size:34px!important;line-height:40px!important;color:#17366f!important}
            body.landing-premium .quick-facts p{color:#65748a;font-size:14px}
            body.landing-premium .section-title-style2{margin-bottom:42px}
            body.landing-premium .section-title-style2 span{font-family:"Muli",sans-serif;font-size:13px;font-weight:600;letter-spacing:3px;color:#315388}
            body.landing-premium .section-title-style2 h2{font-weight:700;letter-spacing:-.01em;color:#172033;max-width:860px;margin-left:auto;margin-right:auto;text-wrap:balance}
            body.landing-premium .section-title-style2 p{max-width:72ch;margin:16px auto 0;color:#65748a;text-wrap:pretty}
            body.landing-premium #overview{background:linear-gradient(180deg,#f7faff 0%,#fff 100%)}
            body.landing-premium #overview .landing-card{height:100%;background:#fff;border:1px solid rgba(36,78,154,.12)!important;border-radius:14px;box-shadow:0 20px 46px rgba(18,43,82,.08)}
            body.landing-premium .course-thumb{position:relative;overflow:hidden;border-radius:10px 10px 0 0}
            body.landing-premium .course-thumb:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(15,30,54,0),rgba(15,30,54,.2));pointer-events:none}
            body.landing-premium .course-thumb img{height:220px;transform:scale(1.001);transition:transform 260ms var(--trinity-ease)}
            body.landing-premium .landing-card:hover .course-thumb img{transform:scale(1.035)}
            body.landing-premium .landing-card{position:relative;border-radius:14px;border:1px solid rgba(36,78,154,.12)!important;background:#fff;box-shadow:0 18px 42px rgba(18,43,82,.08);transition:transform 160ms var(--trinity-ease),box-shadow 180ms ease,border-color 180ms ease}
            body.landing-premium .landing-card:before{content:"";position:absolute;left:18px;right:18px;top:0;height:2px;background:linear-gradient(90deg,rgba(36,78,154,.02),rgba(36,78,154,.62),rgba(36,78,154,.02));opacity:0;transition:opacity 180ms ease}
            body.landing-premium .landing-card:hover{transform:translateY(-3px) scale(1.006);box-shadow:0 28px 60px rgba(18,43,82,.13);border-color:rgba(36,78,154,.24)!important}
            body.landing-premium .landing-card:hover:before{opacity:1}
            body.landing-premium .landing-card h4{font-size:20px;line-height:30px;color:#172033}
            body.landing-premium .landing-card p{color:#667386;line-height:28px}
            body.landing-premium .late-notice-area{background:radial-gradient(circle at top right,rgba(73,112,196,.22),transparent 38%),linear-gradient(135deg,#101720 0%,#141e2e 52%,#0d1420 100%);padding:76px 0}
            body.landing-premium .notice-card{border-radius:10px!important;background:rgba(255,255,255,.96);box-shadow:0 24px 70px rgba(0,0,0,.28)}
            body.landing-premium .notice-list li{color:#536176}
            body.landing-premium .late-stat{border-radius:10px;background:rgba(255,255,255,.08);box-shadow:inset 0 1px 0 rgba(255,255,255,.08);transition:transform 160ms var(--trinity-ease),background-color 180ms ease}
            body.landing-premium .late-stat:hover{transform:translateY(-2px);background:rgba(255,255,255,.12)}
            body.landing-premium #timeline{background:#fff}
            body.landing-premium #timeline .media{background:#fff;border:1px solid rgba(36,78,154,.12);border-radius:14px;overflow:hidden;box-shadow:0 18px 40px rgba(18,43,82,.07);transition:transform 160ms var(--trinity-ease),box-shadow 180ms ease,border-color 180ms ease}
            body.landing-premium #timeline .media:hover{transform:translateY(-3px);box-shadow:0 24px 54px rgba(18,43,82,.11);border-color:rgba(36,78,154,.22)}
            body.landing-premium #timeline .media-head{min-width:156px}
            body.landing-premium #process{background:radial-gradient(circle at 15% 10%,rgba(92,132,214,.22),transparent 34%),linear-gradient(180deg,#101720,#111924)}
            body.landing-premium #process .process-card{background:rgba(255,255,255,.96);border-color:rgba(255,255,255,.18)!important}
            body.landing-premium #fees,body.landing-premium #documents,body.landing-premium #faq{background:#fff}
            body.landing-premium #documents .landing-card i,body.landing-premium #faq .landing-card i{display:inline-grid;width:38px;height:38px;place-items:center;border-radius:50%;background:#eaf2ff;box-shadow:inset 0 0 0 1px rgba(36,78,154,.1)}
            body.landing-premium .contact-info{position:relative;background:#eef4fb}
            body.landing-premium .contact-info:before{opacity:.92}
            body.landing-premium .cta-area{background:linear-gradient(135deg,#101720,#172949)!important}
            body.landing-premium .cta-area .btn-light{box-shadow:0 16px 34px rgba(0,0,0,.22)}
            @media(prefers-reduced-motion:reduce){
                body.landing-premium .slider-content{animation:none!important;transform:none!important;opacity:1!important}
                .faq-item p{animation:none!important}
            }
            @media(max-width:767px){
                .late-notice-area{padding:44px 0 34px}
                .notice-card .card-body{padding:24px}
                .late-stat{margin-top:14px}
                .section-title-style2 h2{font-size:32px;line-height:40px}
                .section-title-style2 span:before,
                .section-title-style2 span:after{display:none!important}
                #process .process-card{min-height:auto}
                #overview,#timeline,#process,#fees,#documents,#faq,#contact{padding-top:42px!important;padding-bottom:42px!important}
                body.landing-premium .slider_item{min-height:720px}
                body.landing-premium .slider-content h1{font-size:42px;line-height:52px;max-width:12ch}
                body.landing-premium .slider-content p{font-size:16px;line-height:28px}
                body.landing-premium .quick-facts{margin-top:0;padding-top:24px!important}
                body.landing-premium .quick-facts .container{border-radius:0;padding:18px 15px}
                body.landing-premium .quick-facts .card-body{border-left:0;border-top:1px solid rgba(36,78,154,.12)}
                body.landing-premium .quick-facts .col-md-3:first-child .card-body{border-top:0}
                body.landing-premium #timeline .media{display:block}
                body.landing-premium #timeline .media-head{width:100%;min-width:0}
            }
        </style>
    </x-slot:styles>

    <x-slot:hero>
        <div class="slider-area owl-carousel has-color">
            <div class="slider_item" style="background: url({{ asset($assetBase.'images/bg/slider-bg1.jpg') }}) center/cover no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                            <div class="slider-content">
                                <h3>{{ $tx("AP Registration '26", "AP 報名 '26") }}</h3>
                                <h1><span class="primary-color">{{ $tx('Taipei Test Center', '台北考場') }}</span> {{ $tx('Registration Support', '報名支援') }}</h1>
                                <p>{{ $tx('Trinity Scholar offers guided AP Exam registration service for students who need Taipei test-center support.', 'Trinity Scholar 為需要台北考場報名支援的學生提供 AP 考試報名服務。') }}</p>
                                <a class="btn btn-primary btn-round btn-lg mt-5" href="{{ route('student-registrations.create') }}">{{ $tx('Start Student Registration', '開始學生報名') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider_item" style="background: url({{ asset($assetBase.'images/bg/slider-bg2.jpg') }}) center/cover no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                            <div class="slider-content">
                                <h3>{{ $tx('Late Registration', '逾期報名') }}</h3>
                                <h1><span class="primary-color">{{ $tx('February 10', '2 月 10 日') }}</span> {{ $tx('Deadline Notice', '截止提醒') }}</h1>
                                <p>{{ $tx('Registration is complete only after the filled-out form and payment are received. Seats may close early when full.', '報名需在表單與付款皆收到後才算完成，名額額滿時可能提前關閉。') }}</p>
                                <a class="btn btn-primary btn-round btn-lg mt-5" href="#late-registration">{{ $tx('View Notice', '查看公告') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="slider_item" style="background: url({{ asset($assetBase.'images/bg/slider-bg3.jpg') }}) center/cover no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                            <div class="slider-content">
                                <h3>{{ $tx('No Login Required', '不需登入') }}</h3>
                                <h1><span class="primary-color">{{ $tx('Submit The Form', '提交表單') }}</span> {{ $tx('Then Admin Reviews', '由管理員審核') }}</h1>
                                <p>{{ $tx('Students can submit passport, exam selections, accommodations, payment method, and preparation interest in one guided flow.', '學生可在同一流程中提交護照、考試選擇、特殊需求、付款方式與備考意願。') }}</p>
                                <a class="btn btn-primary btn-round btn-lg mt-5" href="#process">{{ $tx('See Flow', '查看流程') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:hero>

    <section class="course-area quick-facts pt--80 pb--40">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-5"><div class="card landing-card text-center"><div class="card-body p-25"><h3 class="primary-color">{{ $tx('Feb. 10', '2 月 10 日') }}</h3><p>{{ $tx('Late registration deadline', '逾期報名截止') }}</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card landing-card text-center"><div class="card-body p-25"><h3 class="primary-color">{{ $tx('Taipei', '台北') }}</h3><p>{{ $tx('Test-center support', '考場報名支援') }}</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card landing-card text-center"><div class="card-body p-25"><h3 class="primary-color">{{ $tx('Form + Pay', '表單 + 付款') }}</h3><p>{{ $tx('Both required to complete', '兩者皆完成才算報名') }}</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card landing-card text-center"><div class="card-body p-25"><h3 class="primary-color">{{ $tx('No Login', '不需登入') }}</h3><p>{{ $tx('Students register directly', '學生可直接填寫') }}</p></div></div></div>
            </div>
        </div>
    </section>

    <section id="overview" class="course-area pt--40 pb--100">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>{{ $tx('Program Overview', '服務總覽') }}</span>
                        <h2 class="primary-color">{{ $tx('Trinity Scholar AP Registration Service', 'Trinity Scholar AP 考試報名服務') }}</h2>
                        <p>{{ $overview?->body ?: $tx('Trinity Scholar helps students submit AP registration details, passport documents, exam selections, payment information, and admin verification in one guided platform.', 'Trinity Scholar 協助學生在同一平台提交 AP 報名資料、護照文件、考試選擇、付款資訊，並由管理團隊完成審核。') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card landing-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img1.jpg') }}" alt="{{ $tx('Guided AP registration', 'AP 報名流程') }}"><span class="cs-price primary-bg">AP</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Guided Registration', '引導式報名') }}</a></h4><p>{{ $tx('Student information, guardian contact, passport upload, AP subject choice, accommodations, and payment method are collected in one flow.', '學生資料、家長聯絡資訊、護照上傳、AP 科目選擇、特殊需求與付款方式，皆可在同一流程中完成。') }}</p></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card landing-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/about/abt-right-thumb.jpg') }}" alt="{{ $tx('Coordinator review', '協調員審核') }}"><span class="cs-price primary-bg">{{ $tx('Admin', '審核') }}</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Coordinator Review', '協調員審核') }}</a></h4><p>{{ $tx('The admin team reviews document validity, payment status, subject availability, quota, notes, and final registration status.', '管理團隊會審核文件有效性、付款狀態、科目名額、配額、備註與最終報名狀態。') }}</p></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card landing-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img3.jpg') }}" alt="{{ $tx('Exam preparation', '考試準備') }}"><span class="cs-price primary-bg">{{ $tx('Prep', '備考') }}</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Preparation Interest', '備考課程意願') }}</a></h4><p>{{ $tx('Students can indicate AP preparation, group class, private tutoring, preferred schedule, and preferred language for follow-up.', '學生可填寫 AP 備考、團體課、一對一家教、偏好時段與語言等需求，方便後續聯繫。') }}</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="late-registration" class="late-notice-area">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 white-title text-center compact-section-title">
                        <span>{{ $tx('Late Registration Notice', '逾期報名公告') }}</span>
                        <h2>{{ $tx('2026 AP Late Registration Information', '2026 AP 逾期報名資訊') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-stretch">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card notice-card">
                        <div class="card-body p-25">
                            <span class="notice-kicker">{{ $tx('For Students and Parents', '給學生與家長') }}</span>
                            <h4>{{ $tx('Registration requests are still open for Taipei support.', '台北考場支援仍可提交報名需求。') }}</h4>
                            <p>{{ $tx('Trinity Scholar is accepting AP Late Registration requests for students who need Taipei test-center registration support.', 'Trinity Scholar 目前接受需要台北考場報名支援的學生提交 AP 逾期報名需求。') }}</p>
                            <ul class="notice-list">
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Late registration is available until', '逾期報名開放至') }} <strong>{{ $tx('February 10, 2026', '2026 年 2 月 10 日') }}</strong>{{ $tx('.', '。') }}</span></li>
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Extra late registration fees may apply.', '逾期報名可能會產生額外費用。') }}</span></li>
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Registration is complete only after both the form and payment are received.', '表單與付款皆收到後才算完成報名。') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card notice-card">
                        <div class="card-body p-25">
                            <span class="notice-kicker">{{ $tx('Taipei Test-Center Status', '台北考場狀態') }}</span>
                            <h4>{{ $tx('Some subjects are already marked full.', '部分科目已公告額滿。') }}</h4>
                            <p>{{ $tx('The shared announcement notes that availability is limited and can close before the listed deadline.', '公告提醒名額有限，可能在列出的截止日前提前關閉。') }}</p>
                            <ul class="notice-list">
                                <li><i class="fa fa-exclamation-circle"></i><span><strong>{{ $tx('Marked full:', '已額滿：') }}</strong> AP Chinese, AP Calculus, AP Macro/Micro.</span></li>
                                <li><i class="fa fa-exclamation-circle"></i><span>{{ $tx('Other subjects are processed based on final test-center availability.', '其他科目將依最終考場名額狀態處理。') }}</span></li>
                                <li><i class="fa fa-exclamation-circle"></i><span>{{ $tx('The admin team confirms final status after reviewing the submitted registration.', '管理團隊會在審核提交資料後確認最終狀態。') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="late-stat"><i class="fa fa-calendar"></i><div><span>{{ $tx('Deadline', '截止日') }}</span><strong>{{ $tx('Feb. 10', '2/10') }}</strong></div></div>
                </div>
                <div class="col-md-4">
                    <div class="late-stat"><i class="fa fa-file-text-o"></i><div><span>{{ $tx('Required', '必要條件') }}</span><strong>{{ $tx('Form + Pay', '表單 + 付款') }}</strong></div></div>
                </div>
                <div class="col-md-4">
                    <div class="late-stat"><i class="fa fa-user"></i><div><span>{{ $tx('Review', '審核') }}</span><strong>{{ $tx('Admin Check', '管理員確認') }}</strong></div></div>
                </div>
            </div>
        </div>
    </section>

    <section id="timeline" class="event-area pt--70 pb--60">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title text-center">
                        <span>{{ $tx('Registration Timeline', '報名時程') }}</span>
                        <h2>{{ $tx('Main Period and Late Period', '一般報名與逾期報名時段') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @forelse ($timelines as $round => $items)
                    @foreach ($items as $item)
                        <div class="col-md-6 mb-5">
                            <div class="media align-items-center">
                                <div class="media-head primary-bg">
                                    <span>{{ strtoupper(substr($item->month, 0, 3)) }}</span>
                                    <p>{{ $item->status }}</p>
                                </div>
                                <div class="media-body">
                                    <h4>{{ $round }}</h4>
                                    <p><i class="fa fa-clock-o"></i>{{ $item->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @empty
                    <div class="col-md-6 mb-5">
                        <div class="media align-items-center"><div class="media-head primary-bg"><span>{{ $tx('AUG', '八月') }}</span><p>{{ $tx('OCT', '十月') }}</p></div><div class="media-body"><h4>{{ $tx('Main Registration Period', '一般報名時段') }}</h4><p><i class="fa fa-clock-o"></i>{{ $tx('Standard AP registration window.', '標準 AP 報名期間。') }}</p></div></div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <div class="media align-items-center"><div class="media-head primary-bg"><span>{{ $tx('JAN', '一月') }}</span><p>{{ $tx('MAR', '三月') }}</p></div><div class="media-body"><h4>{{ $tx('Late Registration Period', '逾期報名時段') }}</h4><p><i class="fa fa-clock-o"></i>{{ $tx('Late registration may include additional fees and limited seats.', '逾期報名可能有額外費用且名額有限。') }}</p></div></div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="process" class="teacher-area pt--40 pb--100">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>{{ $process?->eyebrow ?: $tx('Registration Flow', '報名流程') }}</span>
                        <h2 class="primary-color">{{ $process?->title ?: $tx('How students register', '學生如何報名') }}</h2>
                        <p>{{ $process?->body ?: $tx('A direct student form collects the required registration details without login.', '學生可不需登入，直接透過表單提交所需報名資料。') }}</p>
                    </div>
                </div>
            </div>
            <div class="row process-grid">
                @foreach (($process?->items ?: [$tx('Fill student form', '填寫學生資料'), $tx('Select AP exams', '選擇 AP 考試'), $tx('Upload passport', '上傳護照'), $tx('Submit payment proof', '提交付款資料')]) as $index => $item)
                    <div class="col-xl-3 col-lg-3 col-md-6 mb-5 process-col">
                        <div class="card landing-card text-center process-card">
                            <div class="card-body teacher-content p-25">
                                <span class="process-step">{{ $tx('Step', '步驟') }} {{ $index + 1 }}</span>
                                <h4 class="card-title mb-4">{{ $item }}</h4>
                                <p>{{ $tx('Each step is reviewed by the AP registration admin team before completion.', '每個步驟都會由 AP 報名管理團隊審核後確認。') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="fees" class="course-area pt--90 pb--40">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>{{ $tx('Fee Explanation', '費用說明') }}</span>
                        <h2 class="primary-color">{{ $tx('Exam Fee and Service Fee', '考試費與服務費') }}</h2>
                        <p>{{ $tx('Late registration may include extra fees. Final total is calculated from selected subjects and admin-managed fee settings.', '逾期報名可能包含額外費用；最終金額會依選擇科目與管理端設定計算。') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($displayFees as $fee)
                    <div class="col-lg-3 col-md-6 mb-5">
                    <div class="card landing-card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $fee->currency }}</span>
                            <h4>{{ $fee->name }}</h4>
                            <p>{{ $fee->description }}</p>
                            <h3>{{ $tx('Coming Soon', '即將公布') }}</h3>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-3 col-md-6 mb-5">
                    <div class="card landing-card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $tx('Estimated', '預估') }}</span>
                            <h4>{{ $tx('Base Total', '基本總額') }}</h4>
                            <p>{{ $tx('Before subject-specific adjustment, late fees, or practice exam options.', '尚未包含科目差異、逾期費或模擬考選項的調整。') }}</p>
                            <h3>{{ $tx('Coming Soon', '即將公布') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="documents" class="feature-blog pt--40 pb--60">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>{{ $tx('Required Documents', '所需文件') }}</span>
                        <h2>{{ $tx('Document Checklist', '文件檢查清單') }}</h2>
                        <p>{{ $tx('Prepare the core student, parent, passport, exam, and payment information before submitting the form.', '提交表單前請準備學生、家長、護照、考試與付款相關資訊。') }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($displayDocuments as $document)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card landing-card h-100">
                            <div class="card-body p-25">
                                <i class="fa fa-check-circle primary-color mb-3"></i>
                                <h4>{{ $document->name }}</h4>
                                <p>{{ $document->description }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="faq" class="feature-blog pt--50 pb--90">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>{{ $tx('FAQ', '常見問題') }}</span>
                        <h2 class="primary-color">{{ $tx('Frequently Asked Questions', '常見問題') }}</h2>
                    </div>
                </div>
            </div>
            <div class="faq-list">
                @foreach ($displayFaqs as $faq)
                    <details class="faq-item" @if($loop->first) open @endif>
                        <summary>{{ $faq->question }}</summary>
                        <p>{{ $faq->answer }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="contact-info ptb--120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card landing-card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $tx('Contact Information', '聯絡資訊') }}</span>
                            <h3>{{ $contact?->organization ?: 'Trinity Scholar' }}</h3>
                            <p><i class="fa fa-envelope primary-color"></i> {{ $contact?->email ?: 'info@trinityscholar.com' }}</p>
                            <p><i class="fa fa-phone primary-color"></i> {{ $contact?->phone ?: '886-2-2771-6002' }}</p>
                            <p><i class="fa fa-clock-o primary-color"></i> {{ $contact?->office_hours ?: 'Mon-Fri 9:00-18:00' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card landing-card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $privacy?->eyebrow ?: $tx('Privacy', '隱私保護') }}</span>
                            <h3>{{ $privacy?->title ?: $tx('Private documents stay protected', '文件資料皆受保護') }}</h3>
                            <p>{{ $privacy?->body ?: $tx('Passport and payment documents are stored privately and only available to authorized administrators.', '護照與付款文件會以私密方式保存，僅授權管理員可查看。') }}</p>
                            <ul>
                                @foreach (($privacy?->items ?? [$tx('Private passport upload', '護照私密上傳'), $tx('Admin-only document review', '僅管理員可審核文件'), $tx('Audit logging', '操作紀錄留存')]) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-area secondary-bg has-color ptb--50">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <div class="cta-content">
                        <p class="mb-2">{{ $tx('Ready to submit your AP registration?', '準備提交 AP 報名了嗎？') }}</p>
                        <h2>{{ $tx('Start the student registration form', '開始填寫學生報名表') }}</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="cta-btn">
                        <a class="btn btn-light btn-round" href="{{ route('student-registrations.create') }}">{{ $tx('Register Now', '立即報名') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-flow-shell>
