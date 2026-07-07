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
    $assetBase = 'theme/edification/';
    $displayFees = $fees->isNotEmpty() ? $fees : collect([
        (object) [
            'currency' => 'NTD',
            'name' => 'AP Exam Fee',
            'description' => 'Collected for official AP exam registration. Final subject pricing is confirmed by the admin team after review.',
            'amount' => 7800,
        ],
        (object) [
            'currency' => 'NTD',
            'name' => 'Trinity Service Fee',
            'description' => 'Service handling fee for registration coordination, document review, payment checking, and student follow-up.',
            'amount' => 1200,
        ],
        (object) [
            'currency' => 'NTD',
            'name' => 'Late Registration Fee',
            'description' => 'Applied during the late-registration period. Seats are limited and may close before the listed deadline.',
            'amount' => 1500,
        ],
    ]);
    $displayDocuments = $documents->isNotEmpty() ? $documents : collect([
        (object) ['name' => 'Passport', 'description' => 'Clear passport photo page or PDF upload is required for exam registration verification.'],
        (object) ['name' => 'Student Information', 'description' => 'Legal English name, school, grade, student email, phone, nationality, and date of birth.'],
        (object) ['name' => 'Parent Information', 'description' => 'Parent or guardian name, relationship, email, phone, mailing address, city, and postal code.'],
        (object) ['name' => 'AP Exam Selection', 'description' => 'Selected AP subjects, late-registration status, and any practice exam or preparation interest.'],
        (object) ['name' => 'Payment Proof', 'description' => 'Payment must be submitted and verified before the registration can be marked completed.'],
        (object) ['name' => 'Accommodation Documents', 'description' => 'Required only when requesting College Board approved accommodations or SSD support.'],
    ]);
    $displayFaqs = $faqs->isNotEmpty() ? $faqs : collect([
        (object) [
            'question' => 'What is AP?',
            'answer' => 'AP stands for Advanced Placement. AP exams allow students to demonstrate college-level subject knowledge and may support university applications.',
        ],
        (object) [
            'question' => 'Who can register through Trinity Scholar?',
            'answer' => 'Students who need Taipei test-center registration support can submit the student registration form without logging in first.',
        ],
        (object) [
            'question' => 'When is the late-registration deadline?',
            'answer' => 'For the current 2026 late-registration notice, the deadline is February 10, 2026. Registration may close earlier if available seats are filled.',
        ],
        (object) [
            'question' => 'When is registration considered complete?',
            'answer' => 'Registration is complete only after the filled-out form and payment are received, then reviewed by the admin team.',
        ],
        (object) [
            'question' => 'Can I change my exam selection?',
            'answer' => 'Changes depend on deadline, subject availability, quota, and coordinator approval. Students should contact the admin team as early as possible.',
        ],
        (object) [
            'question' => 'Can I request accommodations?',
            'answer' => 'Yes. Students can mark accommodation needs and provide SSD or supporting documentation during registration when applicable.',
        ],
    ]);
    $feeTotal = $displayFees->sum('amount');
@endphp

<x-public-flow-shell :title="$metaTitle" :description="$metaDescription" content-class="none">
    <x-slot:hero>
        <div class="hero-area has-color">
            <div class="container">
                <div class="row">
                <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1">
                    <div class="hero-content">
                        <h3>AP Registration'26</h3>
                        <h1 class="mb-5"><span class="primary-color">Start Your 2026 AP</span><b class="line-break"></b>Registration with Trinity Scholar</h1>
                        <p class="text-white-50">Guided exam registration support for students in Taipei.</p>
                        <form action="{{ route('student-registrations.create') }}" method="GET">
                            <div class="form-input mt-5">
                                <input type="text" name="registration" value="Start your AP registration" readonly>
                                <button class="btn btn-primary btn-round" type="submit">Register</button>
                                <i class="fa fa-search"></i>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </x-slot:hero>

    <section class="course-area pt--80 pb--40">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6 mb-5"><div class="card text-center"><div class="card-body p-25"><h3 class="primary-color">Feb. 10</h3><p>Late registration deadline</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card text-center"><div class="card-body p-25"><h3 class="primary-color">Taipei</h3><p>Test-center support</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card text-center"><div class="card-body p-25"><h3 class="primary-color">Form + Pay</h3><p>Both required to complete</p></div></div></div>
                <div class="col-md-3 col-sm-6 mb-5"><div class="card text-center"><div class="card-body p-25"><h3 class="primary-color">No Login</h3><p>Students register directly</p></div></div></div>
            </div>
        </div>
    </section>

    <section id="overview" class="course-area pt--40 pb--100">
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
                    <div class="card h-100">
                        <div class="card-body p-25">
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
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-25">
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

    <section id="fees" class="course-area pt--90 pb--40">
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
                @foreach ($displayFees as $fee)
                    <div class="col-lg-3 col-md-6 mb-5">
                    <div class="card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $fee->currency }}</span>
                            <h4>{{ $fee->name }}</h4>
                            <p>{{ $fee->description }}</p>
                            <h3>{{ number_format($fee->amount) }}</h3>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="col-lg-3 col-md-6 mb-5">
                    <div class="card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">Estimated</span>
                            <h4>Base Total</h4>
                            <p>Before subject-specific adjustment, late fees, or practice exam options.</p>
                            <h3>NTD {{ number_format($feeTotal) }}</h3>
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
                        <span>Required Documents</span>
                        <h2>Document Checklist</h2>
                        <p>Prepare the core student, parent, passport, exam, and payment information before submitting the form.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($displayDocuments as $document)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100">
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
                        <span>FAQ</span>
                        <h2 class="primary-color">Frequently Asked Questions</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach ($displayFaqs as $faq)
                    <div class="col-lg-6 mb-4">
                        <div class="card h-100">
                            <div class="card-body p-25">
                                <i class="fa fa-question-circle primary-color mb-3"></i>
                                <h4>{{ $faq->question }}</h4>
                                <p>{{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="contact" class="contact-info ptb--120">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">Contact Information</span>
                            <h3>{{ $contact?->organization ?: 'Trinity Scholar' }}</h3>
                            <p><i class="fa fa-envelope primary-color"></i> {{ $contact?->email ?: 'info@trinityscholar.com' }}</p>
                            <p><i class="fa fa-phone primary-color"></i> {{ $contact?->phone ?: '886-2-2771-6002' }}</p>
                            <p><i class="fa fa-clock-o primary-color"></i> {{ $contact?->office_hours ?: 'Mon-Fri 9:00-18:00' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-25">
                            <span class="primary-color text-uppercase d-block mb-3">{{ $privacy?->eyebrow ?: 'Privacy' }}</span>
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
