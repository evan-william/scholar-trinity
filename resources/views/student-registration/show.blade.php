<x-public-flow-shell
    :title="__('student_registration.successful').' | '.$registration->registration_number"
    body-class="registration-confirmation-page"
    content-class="none"
>
    <x-slot:styles>
        <style>
            .registration-confirmation {
                background: #f4f7fb;
                padding: 132px 20px 80px;
                color: #1f2937;
                font-family: "Open Sans", "Microsoft JhengHei", sans-serif;
            }
            .confirmation-shell {
                width: min(1040px, 100%);
                margin: 0 auto;
                background: #fff;
                border: 1px solid #dfe5ee;
                border-radius: 8px;
                overflow: hidden;
                box-shadow: 0 18px 50px rgba(18, 43, 82, .08);
            }
            .confirmation-header {
                display: grid;
                grid-template-columns: minmax(0, 1fr) minmax(220px, auto);
                gap: 32px;
                align-items: end;
                padding: 42px 46px 36px;
                color: #fff;
                background: #142f63;
            }
            .confirmation-eyebrow {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 0 0 13px;
                color: #bcd0f6;
                font-size: 12px;
                font-weight: 700;
                letter-spacing: .08em;
                text-transform: uppercase;
            }
            .confirmation-eyebrow i {
                display: grid;
                width: 26px;
                height: 26px;
                place-items: center;
                color: #142f63;
                background: #fff;
                border-radius: 50%;
            }
            .confirmation-header h1 {
                max-width: 690px;
                margin: 0 0 12px;
                color: #fff;
                font: 600 clamp(30px, 4vw, 46px)/1.15 "Playfair Display", Georgia, serif;
                letter-spacing: 0;
            }
            .confirmation-header p {
                max-width: 720px;
                margin: 0;
                color: rgba(255, 255, 255, .82);
                font-size: 15px;
                line-height: 1.7;
            }
            .confirmation-reference {
                min-width: 230px;
                max-width: 320px;
                padding-left: 30px;
                border-left: 1px solid rgba(255, 255, 255, .2);
            }
            .confirmation-reference span {
                display: block;
                margin-bottom: 7px;
                color: #bcd0f6;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .06em;
                text-transform: uppercase;
            }
            .confirmation-reference strong {
                display: block;
                color: #fff;
                font-size: 19px;
                line-height: 1.35;
                overflow-wrap: anywhere;
            }
            .confirmation-body { padding: 38px 46px 44px; }
            .confirmation-facts {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                margin: 0;
                border-top: 1px solid #e3e8ef;
                border-bottom: 1px solid #e3e8ef;
            }
            .confirmation-facts > div { padding: 22px 24px 22px 0; }
            .confirmation-facts > div + div {
                padding-left: 24px;
                border-left: 1px solid #e3e8ef;
            }
            .confirmation-facts dt {
                margin-bottom: 8px;
                color: #667085;
                font-size: 11px;
                font-weight: 700;
                letter-spacing: .06em;
                text-transform: uppercase;
            }
            .confirmation-facts dd {
                margin: 0;
                color: #172033;
                font-size: 15px;
                font-weight: 600;
                line-height: 1.5;
                overflow-wrap: anywhere;
            }
            .confirmation-status {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                color: #1f6b4f;
            }
            .confirmation-status::before {
                content: "";
                width: 8px;
                height: 8px;
                background: #2f8d68;
                border-radius: 50%;
            }
            .confirmation-selection {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 36px;
                padding: 34px 0;
            }
            .confirmation-selection h2,
            .confirmation-payment h2 {
                margin: 0 0 13px;
                color: #142f63;
                font: 600 22px/1.3 "Playfair Display", Georgia, serif;
                letter-spacing: 0;
            }
            .selection-list {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .selection-list li {
                padding: 7px 10px;
                color: #244e9a;
                background: #eef3fb;
                border: 1px solid #d9e3f4;
                border-radius: 4px;
                font-size: 13px;
                font-weight: 600;
            }
            .selection-empty {
                margin: 0;
                color: #667085;
                font-size: 14px;
            }
            .confirmation-payment {
                display: grid;
                grid-template-columns: minmax(0, 1fr) auto;
                gap: 28px;
                align-items: center;
                padding: 28px 30px;
                background: #f6f8fb;
                border: 1px solid #e1e6ee;
                border-radius: 6px;
            }
            .confirmation-payment p {
                max-width: 670px;
                margin: 0;
                color: #5d687a;
                font-size: 14px;
                line-height: 1.65;
            }
            .confirmation-actions {
                display: flex;
                align-items: center;
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: 10px;
            }
            .confirmation-actions .btn {
                min-height: 40px;
                padding: 10px 17px;
                border-radius: 4px;
                font-size: 12px;
                font-weight: 700;
                white-space: nowrap;
                text-align: center;
            }
            .confirmation-actions .btn-light {
                color: #244e9a;
                background: #fff;
                border: 1px solid #cad5e6;
            }
            .confirmation-privacy {
                display: flex;
                align-items: center;
                gap: 9px;
                margin: 18px 0 0;
                color: #667085;
                font-size: 12px;
                line-height: 1.5;
            }
            .confirmation-privacy i { color: #244e9a; }
            @media (max-width: 991px) {
                .registration-confirmation { padding-top: 96px; }
                .confirmation-header,
                .confirmation-payment { grid-template-columns: 1fr; }
                .confirmation-reference {
                    max-width: none;
                    padding: 20px 0 0;
                    border-top: 1px solid rgba(255,255,255,.2);
                    border-left: 0;
                }
                .confirmation-actions { justify-content: flex-start; }
            }
            @media (max-width: 800px) {
                .registration-confirmation { padding: 76px 14px 50px; }
                .confirmation-header { grid-template-columns: 1fr; padding: 32px 26px; }
                .confirmation-body { padding: 28px 26px 32px; }
                .confirmation-facts,
                .confirmation-selection,
                .confirmation-payment { grid-template-columns: 1fr; }
                .confirmation-facts > div,
                .confirmation-facts > div + div { padding: 17px 0; border-left: 0; }
                .confirmation-facts > div + div { border-top: 1px solid #e3e8ef; }
                .confirmation-selection { gap: 26px; }
                .confirmation-actions .btn { width: 100%; }
            }
            @media (max-width: 575px) {
                .registration-confirmation { padding-top: 58px; }
                .confirmation-header h1 { font-size: 30px; }
                .confirmation-body { padding-inline: 20px; }
            }
        </style>
    </x-slot:styles>

    <main class="registration-confirmation">
        <article class="confirmation-shell" aria-labelledby="confirmation-title">
            <header class="confirmation-header">
                <div>
                    <p class="confirmation-eyebrow">
                        <i class="fa fa-check" aria-hidden="true"></i>
                        {{ __('student_registration.confirmation.eyebrow') }}
                    </p>
                    <h1 id="confirmation-title">{{ __('student_registration.confirmation.title') }}</h1>
                    <p>{{ __('student_registration.confirmation.body') }}</p>
                </div>
                <div class="confirmation-reference">
                    <span>{{ __('student_registration.confirmation.reference') }}</span>
                    <strong>{{ $registration->registration_number }}</strong>
                </div>
            </header>

            <div class="confirmation-body">
                <dl class="confirmation-facts">
                    <div>
                        <dt>{{ __('student_registration.confirmation.status') }}</dt>
                        <dd class="confirmation-status">{{ str_replace('_', ' ', ucfirst($registration->status)) }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('student_registration.confirmation.student') }}</dt>
                        <dd>{{ $registration->student_full_name }}</dd>
                    </div>
                    <div>
                        <dt>{{ __('student_registration.confirmation.submitted_at') }}</dt>
                        <dd>{{ optional($registration->submitted_at)->format('Y-m-d H:i') ?: '-' }}</dd>
                    </div>
                </dl>

                <section class="confirmation-selection">
                    <div>
                        <h2>{{ __('student_registration.confirmation.selected_exams') }}</h2>
                        @if($registration->exams->isNotEmpty())
                            <ul class="selection-list">
                                @foreach($registration->exams as $exam)
                                    <li>{{ $exam->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="selection-empty">-</p>
                        @endif
                    </div>
                    <div>
                        <h2>{{ __('student_registration.confirmation.practice_exams') }}</h2>
                        @if($registration->practiceExamSelections->isNotEmpty())
                            <ul class="selection-list">
                                @foreach($registration->practiceExamSelections as $practiceExam)
                                    <li>{{ $practiceExam->exam_name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="selection-empty">-</p>
                        @endif
                    </div>
                </section>

                <section class="confirmation-payment">
                    <div>
                        <h2>{{ __('student_registration.confirmation.payment_title') }}</h2>
                        <p>{{ __('student_registration.confirmation.payment_body') }}</p>
                    </div>
                    <div class="confirmation-actions">
                        <a class="btn btn-primary" href="{{ route('payments.show', $registration->registration_number) }}">
                            {{ __('student_registration.confirmation.continue_payment') }}
                        </a>
                        <a class="btn btn-light" href="{{ route('landing') }}">
                            {{ __('student_registration.confirmation.back_home') }}
                        </a>
                    </div>
                </section>

                <p class="confirmation-privacy">
                    <i class="fa fa-lock" aria-hidden="true"></i>
                    {{ __('student_registration.confirmation.privacy_notice') }}
                </p>
            </div>
        </article>
    </main>
</x-public-flow-shell>
