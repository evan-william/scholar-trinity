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
    $displayDocuments = collect([
        (object) ['name' => $tx('Student Information', '學生資料'), 'description' => $tx('Legal name, school, grade, date of birth, and a personal student email address. School email addresses should not be used.', '法定姓名、學校、年級、出生日期及學生個人電子郵件。請勿使用學校電子郵件。')],
        (object) ['name' => $tx('Valid Student Passport', '有效學生護照'), 'description' => $tx('Upload a clear copy of the student passport photo page for identity verification.', '上傳清楚的學生護照照片頁，以供身分核對。')],
        (object) ['name' => $tx('Parent Information', '家長資料'), 'description' => $tx('Parent or guardian name, relationship, email, phone, and mailing address.', '家長或監護人姓名、關係、電子郵件、電話及通訊地址。')],
        (object) ['name' => $tx('Accommodation Documents', '特殊考試需求文件'), 'description' => $tx('Provide College Board accommodation approval or SSD documentation when applicable.', '如適用，請提供 College Board 特殊考試需求核准或 SSD 文件。')],
    ]);
    $displayFaqs = ($faqs->isNotEmpty() ? $faqs : collect([
        (object) ['question' => 'What is AP?', 'answer' => 'AP stands for Advanced Placement. AP exams allow students to demonstrate college-level subject knowledge and may support university applications.'],
        (object) ['question' => 'Who can register?', 'answer' => ''],
        (object) ['question' => 'When is registration considered complete?', 'answer' => ''],
        (object) ['question' => 'Can I request accommodations?', 'answer' => 'Yes. Students can mark accommodation needs and provide SSD or supporting documentation during registration.'],
    ]))->map(function ($faq) use ($tx) {
        $question = strtolower($faq->question);
        if (str_contains($question, 'who can register')) {
            return (object) ['question' => $tx('Who can register?', '誰可以報名？'), 'answer' => $tx('Any high school student, homeschooled student, or independent learner.', '任何高中生、在家自學學生或獨立學習者皆可報名。')];
        }
        if (str_contains($question, 'registration considered complete')) {
            return (object) ['question' => $tx('When is registration considered complete?', '何時才算完成報名？'), 'answer' => $tx('Registration is finalized once the form and payment are received and an official confirmation email is issued.', '表單與付款皆收到，且官方確認信寄出後，報名才算完成。')];
        }
        return $faq;
    })->values();
    if (! $displayFaqs->contains(fn ($faq) => str_contains(strtolower($faq->question), 'age requirement'))) {
        $displayFaqs->push((object) ['question' => $tx('What is the age requirement?', '年齡限制為何？'), 'answer' => $tx('There is no minimum age, but AP is designed for high school students in grades 9 through 12. College Board generally does not permit students over age 21 to take the exams.', '沒有最低年齡限制，但 AP 是為 9 至 12 年級高中生設計。College Board 通常不允許 21 歲以上的學生參加考試。')]);
    }
    $processItems = [
        $tx('Fill registration form', '填寫報名表'),
        $tx('Upload documents', '上傳文件'),
        $tx('Review and submit', '確認並提交'),
        $tx('Payment', '付款'),
        $tx('Confirmation', '確認報名'),
    ];
    $processDetails = [
        $tx('Enter student and guardian details, AP subjects or exams, and preparation interests.', '填寫學生與家長資料、AP 科目或考試，以及備考意願。'),
        $tx('Upload a valid passport and any required accommodation documents.', '上傳有效護照及所需的特殊考試需求文件。'),
        $tx('Verify that every detail is correct before submitting the registration.', '提交報名前確認所有資料均正確。'),
        $tx('Select a payment method and complete the required transaction.', '選擇付款方式並完成所需交易。'),
        $tx('The admin team reviews the submission and confirms enrollment by email.', '管理團隊審核資料並以電子郵件確認報名。'),
    ];
    $registrationSettings ??= [];
@endphp

<x-public-flow-shell :title="$metaTitle" :description="$metaDescription" body-class="landing-refined" content-class="none">
    <x-slot:styles>
        <style>
            #overview,#registration-information,#timeline,#process,#documents,#faq,#contact{scroll-margin-top:112px}
            .landing-refined{background:#fff}
            .landing-refined .slider_item{position:relative;min-height:710px}
            .landing-refined .slider_item:before{content:"";position:absolute;inset:0;background:linear-gradient(90deg,rgba(5,11,20,.94) 0%,rgba(8,16,29,.82) 48%,rgba(8,16,29,.24) 100%);z-index:0}
            .landing-refined .slider_item .container{position:relative;z-index:1}
            .landing-refined .slider-content{max-width:690px}
            .landing-refined .slider-content h3{margin-bottom:18px;color:rgba(255,255,255,.82);font-family:var(--trinity-body);font-size:16px;font-weight:500;letter-spacing:0}
            .landing-refined .slider-content h1{max-width:13ch;color:#fff;font-size:58px;line-height:68px;font-weight:700;letter-spacing:0;text-transform:none;text-shadow:0 8px 26px rgba(0,0,0,.32)}
            .landing-refined .slider-content h1 span{color:#a9c2f4!important}
            .landing-refined .slider-content p{max-width:56ch;margin-bottom:0;color:rgba(255,255,255,.78);font-size:18px;font-style:normal;line-height:30px}
            .landing-refined .slider-content .btn{margin-top:30px!important}
            .section-title-style2{margin-bottom:38px;padding-top:0}
            .section-title-style2 span{color:#315388;font-family:var(--trinity-body);font-size:13px;font-weight:600;letter-spacing:0;text-transform:uppercase}
            .section-title-style2 span:before,.section-title-style2 span:after{content:""!important;width:36px!important;height:2px!important;top:50%!important;border-radius:999px!important;background:var(--trinity-blue)!important;background-image:none!important;transform:translateY(-50%)!important}
            .section-title-style2 span:before{left:-52px!important}
            .section-title-style2 span:after{right:-52px!important;left:auto!important}
            .section-title-style2 h2{margin-top:8px;color:var(--trinity-ink);font-size:40px;line-height:50px;font-weight:700;letter-spacing:0}
            .section-title-style2 p{max-width:70ch;margin:14px auto 0;color:#667386;line-height:28px}
            .section-title-style2.white-title span{color:#bfd2f7}
            .section-title-style2.white-title span:before,.section-title-style2.white-title span:after{background:#a9c2f4!important}
            .section-title-style2.white-title h2{color:#fff!important;text-shadow:0 3px 18px rgba(0,0,0,.42)}
            .section-title-style2.white-title p{color:rgba(255,255,255,.76)}
            .landing-refined #header .main-menu nav ul li.active>a,.landing-refined #header .main-menu nav ul li>a:hover{color:#a9c2f4!important}
            .quick-facts{padding:0!important;background:#fff;border-bottom:1px solid var(--trinity-line)}
            .fact-row{display:grid;grid-template-columns:repeat(4,1fr)}
            .fact-item{display:flex;align-items:center;gap:14px;min-height:112px;padding:22px 24px;border-right:1px solid var(--trinity-line)}
            .fact-item:last-child{border-right:0}
            .fact-icon{display:grid;flex:0 0 42px;width:42px;height:42px;place-items:center;border-radius:50%;background:var(--trinity-blue-soft);color:var(--trinity-blue);font-size:17px}
            .fact-item strong{display:block;color:#1d3f79;font-family:var(--trinity-display);font-size:23px;line-height:28px;font-weight:700}
            .fact-item span{display:block;margin-top:3px;color:#748093;font-size:13px;line-height:19px}
            #overview{padding:90px 0 78px;background:#fff}
            .overview-lead{align-items:center;margin-bottom:62px}
            .overview-copy .section-title{margin-bottom:22px}
            .overview-copy .section-title span{color:#315388;font-family:var(--trinity-body);font-size:13px;font-weight:600;letter-spacing:0;text-transform:uppercase}
            .overview-copy .section-title h2{margin-top:8px;font-size:42px;line-height:51px}
            .overview-copy p{margin-bottom:25px;color:#667386;line-height:29px}
            .overview-visual{position:relative;min-height:395px;overflow:hidden;border-radius:6px;background:url('{{ asset('images/taipei-registration-support.jpg') }}') center/cover no-repeat;box-shadow:18px 18px 0 #edf2f8}
            .overview-visual-note{position:absolute;right:0;bottom:0;max-width:250px;padding:19px 22px;color:#fff;background:rgba(20,47,99,.94)}
            .overview-visual-note strong{display:block;color:#fff;font-family:var(--trinity-display);font-size:20px;line-height:26px}
            .overview-visual-note span{display:block;margin-top:5px;color:rgba(255,255,255,.76);font-size:12px;line-height:18px}
            .visual-card{height:100%;overflow:hidden;background:#fff;border:1px solid var(--trinity-line);border-radius:4px;box-shadow:0 12px 28px rgba(24,34,49,.07);transition:transform 160ms var(--trinity-ease),box-shadow 180ms ease}
            .visual-card:hover{transform:translateY(-3px);box-shadow:0 18px 36px rgba(24,34,49,.11)}
            .visual-card .course-thumb{position:relative;overflow:hidden}
            .visual-card .course-thumb img{display:block;width:100%;height:205px;object-fit:cover;transition:transform 260ms var(--trinity-ease)}
            .visual-card:hover .course-thumb img{transform:scale(1.025)}
            .visual-card .card-body{padding:24px!important}
            .visual-card h4{margin-bottom:10px;font-size:20px;line-height:28px}
            .visual-card p{margin:0;color:#697586;line-height:26px}
            .visual-card .cs-price{top:auto;right:0;bottom:0;padding:8px 14px}
            .late-notice-area{position:relative;overflow:hidden;padding:82px 0;background:linear-gradient(rgba(6,13,25,.88),rgba(6,13,25,.9)),url('{{ asset('images/taipei-registration-support.jpg') }}') center/cover no-repeat}
            .late-notice-area .container{position:relative;z-index:1}
            .notice-card{height:100%;overflow:hidden;background:#fff;border:0!important;border-radius:4px;box-shadow:0 16px 36px rgba(0,0,0,.24)}
            .notice-card .card-body{padding:30px}
            .notice-kicker{display:inline-block;margin-bottom:10px;color:var(--trinity-blue);font-size:12px;font-weight:650;letter-spacing:0;text-transform:uppercase}
            .notice-card h4{font-size:23px;line-height:31px}
            .notice-card p{color:#687486;line-height:26px}
            .notice-list{margin:17px 0 0;padding:0}
            .notice-list li{display:flex;gap:10px;padding:5px 0;color:#566274;font-size:14px;font-weight:500;line-height:22px}
            .notice-list li i{margin-top:4px;color:var(--trinity-blue)}
            .late-facts{display:grid;grid-template-columns:repeat(3,1fr);margin-top:22px;border:1px solid rgba(255,255,255,.2);background:rgba(255,255,255,.08)}
            .late-stat{display:flex;align-items:center;gap:14px;min-height:94px;padding:18px 20px;border-right:1px solid rgba(255,255,255,.18)}
            .late-stat:last-child{border-right:0}
            .late-stat i{color:#a9c2f4;font-size:20px}
            .late-stat strong{display:block;color:#fff;font-family:var(--trinity-display);font-size:22px;line-height:27px}
            .late-stat span{display:block;color:rgba(255,255,255,.68);font-size:11px;letter-spacing:0;text-transform:uppercase}
            #timeline{padding:78px 0 58px;background:#f6f8fb}
            #timeline .media{height:100%;overflow:hidden;background:#fff;border:1px solid var(--trinity-line);border-radius:4px;box-shadow:0 10px 24px rgba(24,34,49,.06)}
            #timeline .media-head{min-width:140px}
            #timeline .media-body{padding-right:24px}
            #timeline .media-body h4{font-size:19px;line-height:26px}
            #process{padding:86px 0;background:#fff}
            .process-layout{display:grid;grid-template-columns:minmax(340px,.9fr) minmax(0,1.1fr);align-items:stretch;overflow:hidden;border:1px solid var(--trinity-line);background:#fff;box-shadow:0 18px 46px rgba(24,45,76,.08)}
            .process-photo{position:relative;min-height:530px;overflow:hidden;background:#111a29}
            .process-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(7,13,24,.04) 30%,rgba(7,13,24,.82) 100%)}
            .process-photo img{width:100%;height:100%;object-fit:cover;object-position:center;transform:scale(1.01)}
            .process-photo-caption{position:absolute;right:32px;bottom:32px;left:32px;z-index:1;padding-left:18px;color:#fff;border-left:3px solid #8fb4ff}
            .process-photo-caption strong{display:block;color:#fff;font-family:var(--trinity-display);font-size:24px;line-height:31px}
            .process-photo-caption span{display:block;max-width:34ch;margin-top:7px;color:rgba(255,255,255,.78);font-size:14px;line-height:22px}
            .process-list{display:flex;flex-direction:column;justify-content:center;margin:0;padding:24px 34px;list-style:none;background:#f7f9fc}
            .process-item{display:flex;align-items:flex-start;gap:18px;padding:24px 0;border-bottom:1px solid var(--trinity-line)}
            .process-item:last-child{border-bottom:0}
            .landing-refined .process-list .process-number{display:grid!important;flex:0 0 44px!important;width:44px!important;height:44px!important;place-items:center!important;border:1px solid #244e9a!important;border-radius:4px!important;color:#fff!important;-webkit-text-fill-color:#fff!important;background:#244e9a!important;background-color:#244e9a!important;background-image:none!important;opacity:1!important;font-family:"Open Sans",Arial,sans-serif!important;font-size:15px!important;line-height:44px!important;font-weight:700!important;text-align:center!important;text-shadow:none!important;box-shadow:0 8px 18px rgba(36,78,154,.2)!important}
            .process-item h4{margin:0 0 6px;font-size:19px;line-height:26px}
            .process-item p{max-width:42ch;margin:0;color:#667386;font-size:14px;line-height:22px}
            .fee-item small{color:var(--trinity-blue);font-size:11px;font-weight:650;text-transform:uppercase}
            .fee-item h4{margin:8px 0;font-size:19px;line-height:26px}
            .fee-item p{flex:1;margin:0;color:#6b7686;font-size:13px;line-height:22px}
            .fee-item strong{display:block;margin-top:15px;color:#1d3f79;font-family:var(--trinity-display);font-size:21px}
            #documents{padding:84px 0;background:#fff}
            .documents-layout{display:grid;grid-template-columns:minmax(280px,.75fr) minmax(0,1.25fr);gap:42px;align-items:stretch}
            .documents-photo{position:relative;min-height:520px;overflow:hidden;border-radius:4px;background:url('{{ asset($assetBase.'images/bg/bg1.jpg') }}') center/cover no-repeat}
            .documents-photo:after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,transparent 45%,rgba(7,13,24,.74))}
            .documents-photo-copy{position:absolute;right:26px;bottom:26px;left:26px;z-index:1;color:#fff}
            .documents-photo-copy strong{display:block;color:#fff;font-family:var(--trinity-display);font-size:25px;line-height:32px}
            .documents-photo-copy span{display:block;margin-top:6px;color:rgba(255,255,255,.74);font-size:13px}
            .document-grid{display:grid;grid-template-columns:1fr 1fr;border-top:1px solid var(--trinity-line)}
            .document-item{display:grid;grid-template-columns:34px 1fr;gap:13px;padding:22px 18px;border-right:1px solid var(--trinity-line);border-bottom:1px solid var(--trinity-line)}
            .document-item:nth-child(2n){border-right:0}
            .document-item i{margin-top:2px;color:var(--trinity-blue);font-size:20px}
            .document-item h4{margin:0 0 6px;font-size:17px;line-height:23px}
            .document-item p{margin:0;color:#6b7686;font-size:13px;line-height:21px}
            #faq{padding:78px 0;background:#f6f8fb}
            .faq-list{max-width:920px;margin:0 auto;border-top:1px solid #cfd7e2}
            .faq-item{background:transparent;border-bottom:1px solid #cfd7e2}
            .faq-item summary{position:relative;display:flex;min-height:70px;align-items:center;padding:18px 52px 18px 4px;color:var(--trinity-ink);font-family:var(--trinity-display);font-size:18px;font-weight:700;line-height:26px;cursor:pointer;list-style:none}
            .faq-item summary::-webkit-details-marker{display:none}
            .faq-item summary:after{content:"+";position:absolute;right:6px;display:grid;width:32px;height:32px;place-items:center;border:1px solid #c4cfdd;border-radius:50%;color:var(--trinity-blue);background:#fff;font-family:var(--trinity-body);font-size:20px;font-weight:400;transition:transform 180ms var(--trinity-ease),background-color 180ms ease,color 180ms ease}
            .faq-item[open] summary:after{color:#fff;background:var(--trinity-blue);transform:rotate(45deg)}
            .faq-item p{max-width:760px;margin:0;padding:0 52px 22px 4px;color:#667386;font-size:14px;line-height:25px}
            #contact{padding:68px 0 76px;background:#fff}
            .contact-panel{height:100%;padding:30px;border:1px solid var(--trinity-line);border-radius:4px;background:#fff}
            .contact-panel h3{margin-bottom:18px;font-size:27px;line-height:34px}
            .contact-panel p{margin-bottom:10px}
            .contact-panel ul{margin-top:18px}
            .contact-panel li{margin-bottom:8px;color:#667386}
            .cta-area{background:#101720!important}
            @media(max-width:991px){
                .fact-row{grid-template-columns:1fr 1fr}.fact-item:nth-child(2){border-right:0}.fact-item:nth-child(-n+2){border-bottom:1px solid var(--trinity-line)}
                .overview-visual{margin-top:34px}.process-layout,.fee-layout,.documents-layout{grid-template-columns:1fr}.process-photo,.fee-visual,.documents-photo{min-height:390px}
            }
            @media(max-width:767px){
                .landing-refined .slider_item{min-height:640px}.landing-refined .slider-content h1{font-size:42px;line-height:50px}.landing-refined .slider-content p{font-size:16px;line-height:27px}
                .section-title-style2 h2,.overview-copy .section-title h2{font-size:31px;line-height:39px}.section-title-style2 span:before,.section-title-style2 span:after{display:none!important}
                #overview,.late-notice-area,#timeline,#process,#documents,#faq,#contact{padding-top:58px;padding-bottom:58px}
                .late-facts{grid-template-columns:1fr}.late-stat{border-right:0;border-bottom:1px solid rgba(255,255,255,.18)}.late-stat:last-child{border-bottom:0}
                #timeline .media{display:block}#timeline .media-head{width:100%;min-width:0}#timeline .media-body{padding:20px}
                .fee-grid,.document-grid{grid-template-columns:1fr}.fee-item,.document-item{border-right:0}.fee-item:nth-last-child(2){border-bottom:1px solid var(--trinity-line)}
            }
            @media(max-width:575px){
                .fact-row{grid-template-columns:1fr}.fact-item{min-height:90px;border-right:0;border-bottom:1px solid var(--trinity-line)!important}.fact-item:last-child{border-bottom:0!important}
                .overview-visual,.process-photo,.fee-visual,.documents-photo{min-height:320px}.overview-visual{box-shadow:10px 10px 0 #edf2f8}.notice-card .card-body{padding:24px}.fee-grid{padding:12px}.contact-panel{padding:24px}
            }
        </style>
    </x-slot:styles>

    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'EducationalOrganization',
        'name' => 'Trinity Scholar',
        'url' => url('/'),
        'email' => 'ap-registration@trinityscholar.com',
        'telephone' => '886-2-2771-6002',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $registrationSettings['test_site_address_en'] ?? 'No. 99, Meide St, Shilin District',
            'addressLocality' => 'Taipei City',
            'postalCode' => '11159',
            'addressCountry' => 'TW',
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <x-slot:hero>
        <div class="slider-area owl-carousel has-color">
            <div class="slider_item" style="background: url({{ asset('images/taipei-registration-support.jpg') }}) center/cover no-repeat;">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-7 col-md-9">
                            <div class="slider-content">
                                <h3>{{ $tx('AP Registration Support', 'AP 報名支援') }}</h3>
                                <h1><span class="primary-color">{{ $heroTitle }}</span></h1>
                                <p>{{ $heroIntro }}</p>
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
                                <h3>{{ $tx('Registration Windows', '報名時段') }}</h3>
                                <h1><span class="primary-color">{{ $tx('Plan Early', '提早準備') }}</span> {{ $tx('For AP Registration', 'AP 考試報名') }}</h1>
                                <p>{{ $tx('Main registration runs from August through October. Late registration may open during the spring semester if seats remain available.', '一般報名期間為八月至十月；若仍有名額，逾期報名可能於春季學期開放。') }}</p>
                                <a class="btn btn-primary btn-round btn-lg mt-5" href="#registration-information">{{ $tx('View Registration Information', '查看報名資訊') }}</a>
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

    <section class="quick-facts" aria-label="{{ $tx('Registration highlights', '報名重點') }}">
        <div class="container">
            <div class="fact-row">
                <div class="fact-item"><i class="fa fa-calendar fact-icon"></i><div><strong>{{ $registrationSettings['main_period'] ?? 'August - October' }}</strong><span>{{ $tx('Main registration period', '一般報名時段') }}</span></div></div>
                <div class="fact-item"><i class="fa fa-map-marker fact-icon"></i><div><strong>{{ $tx('Taipei', '台北') }}</strong><span>{{ $tx('Test-center support', '考場報名支援') }}</span></div></div>
                <div class="fact-item"><i class="fa fa-check-square-o fact-icon"></i><div><strong>{{ $tx('Form + Pay', '表單 + 付款') }}</strong><span>{{ $tx('Both required to complete', '兩者皆完成才算報名') }}</span></div></div>
                <div class="fact-item"><i class="fa fa-unlock-alt fact-icon"></i><div><strong>{{ $tx('No Login', '不需登入') }}</strong><span>{{ $tx('Students register directly', '學生可直接填寫') }}</span></div></div>
            </div>
        </div>
    </section>

    <section id="overview" class="about-area">
        <div class="container">
            <div class="row overview-lead">
                <div class="col-lg-6">
                    <div class="overview-copy">
                        <div class="section-title">
                            <span>{{ $tx('Program Overview', '服務總覽') }}</span>
                            <h2>{{ $tx('A clearer route to AP exam registration', '更清楚的 AP 考試報名流程') }}</h2>
                        </div>
                        <p>{{ $overview?->body ?: $tx('Trinity Scholar helps students submit AP registration details, passport documents, exam selections, payment information, and admin verification in one guided platform.', 'Trinity Scholar 協助學生在同一平台提交 AP 報名資料、護照文件、考試選擇、付款資訊，並由管理團隊完成審核。') }}</p>
                        <a class="btn btn-primary btn-round" href="{{ route('student-registrations.create') }}">{{ $tx('Start Registration', '開始報名') }}</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="overview-visual" role="img" aria-label="{{ $tx('Taipei test-center support', '台北考場報名支援') }}">
                        <div class="overview-visual-note"><strong>{{ $tx('Taipei test-center support', '台北考場報名支援') }}</strong><span>{{ $tx('Registration coordination from form submission through admin review.', '從表單提交到管理端審核的完整報名協調。') }}</span></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-5">
                    <article class="visual-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img1.jpg') }}" alt="{{ $tx('Guided AP registration', 'AP 報名流程') }}"><span class="cs-price primary-bg">AP</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Guided Registration', '引導式報名') }}</a></h4><p>{{ $tx('Student information, guardian contact, passport upload, AP subject choice, accommodations, and payment method are collected in one flow.', '學生資料、家長聯絡資訊、護照上傳、AP 科目選擇、特殊需求與付款方式，皆可在同一流程中完成。') }}</p></div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <article class="visual-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/about/abt-right-thumb.jpg') }}" alt="{{ $tx('Coordinator review', '協調員審核') }}"><span class="cs-price primary-bg">{{ $tx('Admin', '審核') }}</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Coordinator Review', '協調員審核') }}</a></h4><p>{{ $tx('The admin team reviews document validity, payment status, subject availability, quota, notes, and final registration status.', '管理團隊會審核文件有效性、付款狀態、科目名額、配額、備註與最終報名狀態。') }}</p></div>
                    </article>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <article class="visual-card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img3.jpg') }}" alt="{{ $tx('Exam preparation', '考試準備') }}"><span class="cs-price primary-bg">{{ $tx('Prep', '備考') }}</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">{{ $tx('Preparation Interest', '備考課程意願') }}</a></h4><p>{{ $tx('Students can indicate AP preparation, group class, private tutoring, preferred schedule, and preferred language for follow-up.', '學生可填寫 AP 備考、團體課、一對一家教、偏好時段與語言等需求，方便後續聯繫。') }}</p></div>
                    </article>
                </div>
            </div>
        </div>
    </section>

    <section id="registration-information" class="late-notice-area">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 white-title text-center compact-section-title">
                        <span>{{ $tx('Registration Information', '報名資訊') }}</span>
                        <h2>{{ $tx('Plan for the AP registration cycle', '規劃 AP 考試報名時程') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-stretch">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card notice-card">
                        <div class="card-body p-25">
                            <span class="notice-kicker">{{ $tx('Main Registration', '一般報名') }}</span>
                            <h4>{{ $tx('Prepare during the main registration window.', '請於一般報名期間提早準備。') }}</h4>
                            <p>{{ $tx('Students and guardians can prepare personal details, exam choices, and required documents before submitting the guided form.', '學生與家長可先準備個人資料、考試選擇及所需文件，再提交引導式表單。') }}</p>
                            <ul class="notice-list">
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Main registration period:', '一般報名期間：') }} <strong>{{ $registrationSettings['main_period'] ?? 'August - October' }}</strong></span></li>
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Main AP testing is usually held at the beginning of May.', 'AP 一般考試通常於五月初舉行。') }}</span></li>
                                <li><i class="fa fa-check-circle"></i><span>{{ $tx('Registration is finalized after the form, payment, and official confirmation email are received.', '表單與付款皆收到，且官方確認信寄出後，報名才算完成。') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card notice-card">
                        <div class="card-body p-25">
                            <span class="notice-kicker">{{ $tx('Late Registration', '逾期報名') }}</span>
                            <h4>{{ $tx('Late registration depends on remaining capacity.', '逾期報名視剩餘名額開放。') }}</h4>
                            <p>{{ $tx('Late registration may open during the spring semester after main registration closes, only when test-center slots remain available.', '一般報名結束後，若考場仍有名額，逾期報名可能於春季學期開放。') }}</p>
                            <ul class="notice-list">
                                <li><i class="fa fa-info-circle"></i><span>{{ $tx('Usual late registration period:', '通常逾期報名期間：') }} <strong>{{ $registrationSettings['late_period'] ?? 'January - March' }}</strong></span></li>
                                <li><i class="fa fa-info-circle"></i><span>{{ $tx('Late testing is usually held in mid to late May.', '逾期考試通常於五月中下旬舉行。') }}</span></li>
                                <li><i class="fa fa-info-circle"></i><span>{{ $tx('Final availability is confirmed by the admin team after review.', '最終名額由管理團隊審核後確認。') }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="late-facts">
                <div class="late-stat"><i class="fa fa-calendar"></i><div><span>{{ $tx('Main Registration', '一般報名') }}</span><strong>{{ $registrationSettings['main_period'] ?? 'August - October' }}</strong></div></div>
                <div class="late-stat"><i class="fa fa-calendar-check-o"></i><div><span>{{ $tx('Main Test Period', '一般考試時段') }}</span><strong>{{ $registrationSettings['main_test_period'] ?? 'Beginning of May' }}</strong></div></div>
                <div class="late-stat"><i class="fa fa-clock-o"></i><div><span>{{ $tx('Late Test Period', '逾期考試時段') }}</span><strong>{{ $registrationSettings['late_test_period'] ?? 'Mid to late May' }}</strong></div></div>
            </div>
        </div>
    </section>

    <section id="timeline" class="event-area pt--70 pb--60">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title text-center">
                        <span>{{ $tx('Registration Timeline', '報名時程') }}</span>
                        <h2>{{ $tx('Registration and test periods', '報名與考試時段') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ([
                    [$tx('AUG', '八月'), $tx('OCT', '十月'), $tx('Main Registration Period', '一般報名時段'), $tx('Regular registration is normally available from August through October.', '一般報名通常於八月至十月開放。')],
                    [$tx('JAN', '一月'), $tx('MAR', '三月'), $tx('Late Registration Period', '逾期報名時段'), $tx('After main registration closes, late registration may open from January through March if slots remain.', '一般報名結束後，若仍有名額，逾期報名可能於一月至三月開放。')],
                    [$tx('EARLY', '五月初'), $tx('MAY', '考試'), $tx('Main Test Period', '一般考試時段'), $tx('Regular AP test dates are usually scheduled at the beginning of May.', 'AP 一般考試日期通常安排於五月初。')],
                    [$tx('MID', '五月中'), $tx('LATE', '下旬'), $tx('Late Test Period', '逾期考試時段'), $tx('Late AP test dates are usually scheduled in mid to late May.', 'AP 逾期考試日期通常安排於五月中下旬。')],
                ] as $item)
                    <div class="col-md-6 mb-5"><div class="media align-items-center"><div class="media-head primary-bg"><span>{{ $item[0] }}</span><p>{{ $item[1] }}</p></div><div class="media-body"><h4>{{ $item[2] }}</h4><p><i class="fa fa-clock-o"></i>{{ $item[3] }}</p></div></div></div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="process" class="registration-process">
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
            <div class="process-layout">
                <div class="process-photo">
                    <img src="{{ asset('images/taipei-registration-support.jpg') }}" alt="{{ $tx('Student preparing registration information in Taipei', '學生在台北準備報名資料') }}">
                    <div class="process-photo-caption"><strong>{{ $tx('One guided submission', '一次完成引導式提交') }}</strong><span>{{ $tx('No student account is required before starting the form.', '開始填寫表單前不需要建立學生帳號。') }}</span></div>
                </div>
                <ol class="process-list">
                    @foreach ($processItems as $index => $item)
                        <li class="process-item">
                            <span class="process-number">{{ $index + 1 }}</span>
                            <div><h4>{{ $item }}</h4><p>{{ $processDetails[$index] ?? $tx('Reviewed by the AP registration admin team before completion.', '完成前由 AP 報名管理團隊審核。') }}</p></div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    </section>

    <section id="documents" class="required-documents">
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
            <div class="documents-layout">
                <div class="documents-photo" role="img" aria-label="{{ $tx('Preparing registration documents', '準備報名文件') }}">
                    <div class="documents-photo-copy"><strong>{{ $tx('Prepare once, submit with confidence', '一次備妥，安心提交') }}</strong><span>{{ $tx('Use clear and accurate files so the admin review can move faster.', '提供清楚且正確的文件，協助管理團隊加快審核。') }}</span></div>
                </div>
                <div class="document-grid">
                    @foreach ($displayDocuments as $document)
                        <article class="document-item">
                            <i class="fa fa-check-circle"></i>
                            <div><h4>{{ $document->name }}</h4><p>{{ $document->description }}</p></div>
                        </article>
                    @endforeach
                </div>
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

    <section id="contact" class="contact-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="contact-panel">
                        <span class="primary-color text-uppercase d-block mb-3">{{ $tx('Contact Information', '聯絡資訊') }}</span>
                        <h3>{{ $contact?->organization ?: 'Trinity Scholar' }}</h3>
                        <p><i class="fa fa-envelope primary-color"></i> ap-registration@trinityscholar.com</p>
                        <p><i class="fa fa-phone primary-color"></i> 886-2-2771-6002</p>
                        <p><i class="fa fa-comment primary-color"></i> Line: @TrinityScholar</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="contact-panel">
                        <span class="primary-color text-uppercase d-block mb-3">{{ $privacy?->eyebrow ?: $tx('Privacy', '隱私保護') }}</span>
                        <h3>{{ $privacy?->title ?: $tx('Private documents stay protected', '文件資料皆受保護') }}</h3>
                        <p>{{ $privacy?->body ?: $tx('Passport and payment documents are stored privately and only available to authorized administrators.', '護照與付款文件會以私密方式保存，僅授權管理員可查看。') }}</p>
                        <ul>
                            @foreach (($privacy?->items ?? [$tx('Private passport upload', '護照私密上傳'), $tx('Admin-only document review', '僅管理員可審核文件'), $tx('Audit logging', '操作紀錄留存')]) as $item)
                                <li><i class="fa fa-lock primary-color"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
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
