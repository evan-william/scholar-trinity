@php
    $seo = $settings->get('seo', collect());
    $hero = $settings->get('hero', collect());
    $metaTitle = data_get($seo, 'meta_title.text', '2026 AP Exam Registration | Trinity Scholar');
    $metaDescription = data_get($seo, 'meta_description.text', 'Trinity Scholar AP Exam registration service for students in Taipei.');
    $heroTitle = data_get($hero, 'title.text', '2026 Advanced Placement (AP) Exam Registration');
    $heroIntro = data_get($hero, 'introduction.text', 'Trinity Scholar offers hassle-free AP Exam registration service for students who need test-center registration support in Taipei.');
    $overview = $sections->get('overview');
    $process = $sections->get('process');
    $privacy = $sections->get('privacy');
    $feeTotal = $fees->sum('amount');
    $assetBase = 'theme/edification/';
@endphp

<x-public-flow-shell :title="$metaTitle" :description="$metaDescription" content-class="">
    <x-slot:hero>
        <div class="hero-area has-color">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 offset-lg-1 col-md-10 offset-md-1">
                        <div class="hero-content">
                            <h3>Taipei Test Center Support</h3>
                            <h1 class="mb-5"><span class="primary-color">2026 AP Exam</span><b class="line-break"></b>Registration Support</h1>
                            <p class="text-white-50">{{ $heroIntro }}</p>
                            <div class="mt-5">
                                <a class="btn btn-primary btn-round mr-3 mb-3" href="{{ route('student-registrations.create') }}">Start Student Registration</a>
                                <a class="btn btn-light btn-round mb-3" href="#late-registration">Learn More</a>
                            </div>
                            <p class="text-white-50 mb-0">Late registration deadline: <strong class="text-white">February 10, 2026</strong>. Registration is complete only after the form and payment are received.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:hero>

    @push('styles')
        <style>
            .ts-stat-row{margin-top:-48px;position:relative;z-index:2}
            .ts-stat{background:#fff;border:1px solid #efefef;padding:25px;text-align:center;box-shadow:0 2px 18.9px 8.1px rgba(204,204,223,.2);height:100%}
            .ts-stat h3{color:#fc9928;font-size:32px;line-height:38px}
            .ts-stat p{margin:8px 0 0;font-size:14px;color:#7d7d7d}
            .ts-notice{border-left:5px solid #fc9928;background:#fff8ed;padding:28px 30px;height:100%;box-shadow:0 0 21px 11px rgba(204,204,223,.12)}
            .ts-notice h4{margin-bottom:15px}
            .ts-notice ul{padding-left:20px;margin-bottom:0}
            .ts-notice li{margin-bottom:8px}
            .ts-process .media{height:100%;background:#fff}
            .ts-process .media-head{width:112px}
            .ts-fee-card{height:100%;background:#fff;border:1px solid #efefef;padding:25px;transition:all .3s ease}
            .ts-fee-card:hover{box-shadow:0 -6px 24px rgba(10,10,10,.09)}
            .ts-fee-card .amount{font-size:28px;color:#252525;font-family:"Roboto Slab",serif;font-weight:700}
            .ts-doc-card{display:flex;gap:15px;background:#fff;border:1px solid #efefef;padding:24px;height:100%}
            .ts-doc-card i{font-size:26px;color:#fc9928;margin-top:3px}
            .ts-faq details{background:#fff;border:1px solid #efefef;padding:18px 22px;margin-bottom:12px}
            .ts-faq summary{cursor:pointer;font-family:"Roboto Slab",serif;font-weight:700;color:#252525}
            .ts-faq p{margin:12px 0 0}
            .ts-contact-card{background:#fff;border:1px solid #efefef;padding:30px;height:100%}
            @media(max-width:767px){.ts-stat-row{margin-top:0}.ts-process .media{display:block}.ts-process .media-head{width:100%}.ts-process .media-body{padding:20px;text-align:left}}
        </style>
    @endpush

    <section class="ts-stat-row">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-4"><div class="ts-stat"><h3>Feb. 10</h3><p>Late registration deadline</p></div></div>
                <div class="col-md-3 col-sm-6 mb-4"><div class="ts-stat"><h3>Taipei</h3><p>Test-center support</p></div></div>
                <div class="col-md-3 col-sm-6 mb-4"><div class="ts-stat"><h3>Form + Pay</h3><p>Both required to complete</p></div></div>
                <div class="col-md-3 col-sm-6 mb-4"><div class="ts-stat"><h3>No Login</h3><p>Students register directly</p></div></div>
            </div>
        </div>
    </section>

    <section id="overview" class="course-area pt--80 pb--100">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>Program Overview</span>
                        <h2 class="primary-color">Trinity Scholar AP Registration Service</h2>
                        <p>{{ $overview?->body ?: 'Trinity Scholar helps students submit AP registration details, passport documents, exam selections, payment information, and admin verification in one guided platform.' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img1.jpg') }}" alt="Guided AP registration"><span class="cs-price primary-bg">AP</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">Guided Registration</a></h4><p>Student information, guardian contact, passport upload, AP subject choice, accommodations, and payment method are collected in one flow.</p></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/about/abt-right-thumb.jpg') }}" alt="Coordinator review"><span class="cs-price primary-bg">Admin</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">Coordinator Review</a></h4><p>The admin team reviews document validity, payment status, subject availability, quota, notes, and final registration status.</p></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="card">
                        <div class="course-thumb"><img src="{{ asset($assetBase.'images/course/cs-img3.jpg') }}" alt="Exam preparation"><span class="cs-price primary-bg">Prep</span></div>
                        <div class="card-body p-25"><h4><a href="{{ route('student-registrations.create') }}">Preparation Interest</a></h4><p>Students can indicate AP preparation, group class, private tutoring, preferred schedule, and preferred language for follow-up.</p></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="late-registration" class="take-toure-area ptb--120">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 white-title text-center">
                        <span>Late Registration Notice</span>
                        <h2>2026 AP Late Registration Information</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="ts-notice">
                        <h4>For Students and Parents</h4>
                        <p>Trinity Scholar is accepting AP Late Registration requests for students who need Taipei test-center registration support.</p>
                        <ul>
                            <li>Late registration is available until <strong>February 10, 2026</strong>.</li>
                            <li>There is an extra fee for late registration.</li>
                            <li>Seats are limited and may close before the deadline.</li>
                            <li>Registration is complete only after both the form and payment are received.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="ts-notice">
                        <h4>Taipei Test-Center Status</h4>
                        <p>The shared announcement notes that some subjects are already full at the Taipei test center.</p>
                        <ul>
                            <li><strong>Marked full:</strong> AP Chinese, AP Calculus, and AP Macro/Micro.</li>
                            <li>Other subjects are processed based on final test-center availability.</li>
                            <li>The admin team confirms the final status after reviewing the submitted registration.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="timeline" class="event-area pt--120 pb--80">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title text-center">
                        <span>Registration Timeline</span>
                        <h2>Main Period and Late Period</h2>
                    </div>
                </div>
            </div>
            <div class="row ts-process">
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
                        <div class="media align-items-center"><div class="media-head primary-bg"><span>AUG</span><p>OCT</p></div><div class="media-body"><h4>Main Registration Period</h4><p><i class="fa fa-clock-o"></i>Standard AP registration window.</p></div></div>
                    </div>
                    <div class="col-md-6 mb-5">
                        <div class="media align-items-center"><div class="media-head primary-bg"><span>JAN</span><p>MAR</p></div><div class="media-body"><h4>Late Registration Period</h4><p><i class="fa fa-clock-o"></i>Late registration may include additional fees and limited seats.</p></div></div>
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
                        <span>{{ $process?->eyebrow ?: 'Registration Flow' }}</span>
                        <h2 class="primary-color">{{ $process?->title ?: 'How students register' }}</h2>
                        <p>{{ $process?->body ?: 'A direct student form collects the required registration details without login.' }}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach (($process?->items ?: ['Fill student form', 'Select AP exams', 'Upload passport', 'Submit payment proof']) as $index => $item)
                    <div class="col-lg-3 col-md-6 mb-5">
                        <div class="card text-center">
                            <div class="card-body teacher-content p-25">
                                <span class="primary-color d-block mb-4">Step {{ $index + 1 }}</span>
                                <h4 class="card-title mb-4">{{ $item }}</h4>
                                <p>Each step is reviewed by the AP registration admin team before completion.</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="fees" class="course-area pt--100 pb--80">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>Fee Explanation</span>
                        <h2 class="primary-color">Exam Fee and Service Fee</h2>
                        <p>Late registration may include extra fees. Final total is calculated from selected subjects and admin-managed fee settings.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($fees as $fee)
                    <div class="col-lg-4 col-md-6 mb-5">
                        <div class="ts-fee-card">
                            <span class="trinity-meta">{{ $fee->currency }}</span>
                            <h4>{{ $fee->name }}</h4>
                            <p>{{ $fee->description }}</p>
                            <div class="amount">{{ number_format($fee->amount) }}</div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="ts-fee-card">
                        <span class="trinity-meta">Estimated</span>
                        <h4>Base Total</h4>
                        <p>Before subject-specific adjustment, late fees, or practice exam options.</p>
                        <div class="amount">NTD {{ number_format($feeTotal) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="documents" class="feature-blog pt--80 pb--80">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>Required Documents</span>
                        <h2>Document Checklist</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($documents as $document)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="ts-doc-card">
                            <i class="fa fa-check-circle"></i>
                            <div><h4>{{ $document->name }}</h4><p>{{ $document->description }}</p></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="faq" class="pt--80 pb--80 ts-faq">
        <div class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="section-title-style2 black-title title-tb text-center">
                        <span>FAQ</span>
                        <h2 class="primary-color">Frequently Asked Questions</h2>
                    </div>
                    @foreach ($faqs as $faq)
                        <details>
                            <summary>{{ $faq->question }}</summary>
                            <p>{{ $faq->answer }}</p>
                        </details>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact-info ptb--120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="ts-contact-card">
                        <span class="trinity-meta">Contact Information</span>
                        <h3>{{ $contact?->organization ?: 'Trinity Scholar' }}</h3>
                        <p><i class="fa fa-envelope primary-color"></i> {{ $contact?->email ?: 'info@trinityscholar.com' }}</p>
                        <p><i class="fa fa-phone primary-color"></i> {{ $contact?->phone ?: '886-2-2771-6002' }}</p>
                        <p><i class="fa fa-clock-o primary-color"></i> {{ $contact?->office_hours ?: 'Mon-Fri 9:00-18:00' }}</p>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="ts-contact-card">
                        <span class="trinity-meta">{{ $privacy?->eyebrow ?: 'Privacy' }}</span>
                        <h3>{{ $privacy?->title ?: 'Private documents stay protected' }}</h3>
                        <p>{{ $privacy?->body ?: 'Passport and payment documents are stored privately and only available to authorized administrators.' }}</p>
                        <ul>
                            @foreach (($privacy?->items ?? ['Private passport upload', 'Admin-only document review', 'Audit logging']) as $item)
                                <li>{{ $item }}</li>
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
                        <p class="mb-2">Ready to submit your AP registration?</p>
                        <h2>Start the student registration form</h2>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="cta-btn">
                        <a class="btn btn-light btn-round" href="{{ route('student-registrations.create') }}">Register Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-public-flow-shell>
