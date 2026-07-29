<x-admin-shell
    :title="$registration->registration_number"
    :subtitle="__('admin.registration_detail_subtitle')"
>
    <x-slot:styles>
        <style>
            .registration-detail { display: grid; gap: 16px; }
            .registration-detail .card + .card { margin-top: 0; }
            .registration-toolbar {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
                padding: 18px 20px;
                border-top: 3px solid var(--blue);
            }
            .registration-toolbar h2 {
                margin: 0 0 8px;
                color: var(--navy);
                font-size: 20px;
                font-weight: 700;
            }
            .registration-toolbar .summary-line {
                display: flex;
                flex-wrap: wrap;
                gap: 8px 18px;
                margin: 0;
                color: var(--muted);
                font-size: 12px;
            }
            .registration-toolbar .summary-line strong {
                color: var(--ink);
                font-weight: 600;
            }
            .registration-detail .status {
                padding: 4px 9px;
                border: 1px solid #cdd9eb;
                background: #f1f5fb;
                color: var(--navy);
            }
            .registration-detail > .grid { align-items: start; }
            .registration-detail .card { min-width: 0; }
            .registration-detail table { table-layout: fixed; }
            .registration-detail td {
                padding: 9px 4px;
                color: #344054;
                line-height: 1.5;
                overflow-wrap: anywhere;
            }
            .registration-detail td:first-child {
                width: 36%;
                padding-right: 16px;
                color: #667085;
                font-size: 12px;
                font-weight: 600;
            }
            .registration-detail tr:last-child td { border-bottom: 0; }
            .registration-detail h3 {
                margin: 20px 0 12px;
                color: var(--navy);
                font-size: 14px;
                font-weight: 700;
            }
            .registration-detail .card form + form {
                margin-top: 22px;
                padding-top: 20px;
                border-top: 1px solid #e4e8ef;
            }
            .registration-detail .note {
                border-radius: 4px;
                background: #f8fafc;
            }
            .registration-detail .timeline {
                border-left-width: 2px;
                border-left-color: #bdc9da;
            }
            .registration-detail .timeline div {
                padding: 0 0 12px 2px;
                color: #475467;
                font-size: 13px;
                line-height: 1.5;
            }
            .registration-detail .empty-copy {
                margin: 8px 0 0;
                color: var(--muted);
                font-size: 12px;
            }
            @media (max-width: 860px) {
                .registration-toolbar { align-items: flex-start; flex-direction: column; }
                .registration-detail > .grid { grid-template-columns: 1fr; }
            }
        </style>
    </x-slot:styles>

    <div class="registration-detail">
    <div class="card registration-toolbar">
        <div>
            <h2>{{ $registration->student_full_name }}</h2>
            <p class="summary-line">
                <span>{{ __('admin.reference') }}: <strong>{{ $registration->registration_number }}</strong></span>
                <span>{{ __('admin.status') }}: <strong>{{ str_replace('_', ' ', $registration->status) }}</strong></span>
                <span>{{ __('admin.submitted_at') }}: <strong>{{ optional($registration->submitted_at)->format('Y-m-d H:i') ?: '-' }}</strong></span>
            </p>
        </div>
        <div class="actions">
            <a class="btn light" href="{{ route('admin.student-registrations.index') }}">{{ __('admin.back') }}</a>
            <a class="btn light" href="{{ route('admin.student-registrations.edit',$registration) }}">{{ __('admin.edit') }}</a>
            <a class="btn light" href="{{ route('admin.student-registrations.print',$registration) }}">{{ __('admin.print') }}</a>
            <form method="POST" action="{{ route('admin.student-registrations.destroy',$registration) }}" onsubmit="return confirm('Delete this registration permanently?')">@csrf @method('DELETE')<button class="btn danger" type="submit">{{ __('admin.delete') }}</button></form>
        </div>
    </div>

    <div class="grid">
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.student_information') }}</h2></div>
            <table>
                <tr><td>{{ __('admin.full_name') }}</td><td>{{ $registration->student_full_name }}</td></tr>
                <tr><td>{{ __('admin.english_legal_name') }}</td><td>{{ collect([$registration->family_name_en, $registration->first_name_en, $registration->middle_name])->filter()->implode(' ') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.chinese_legal_name') }}</td><td>{{ $registration->chinese_legal_name ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.preferred_name') }}</td><td>{{ $registration->preferred_name ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.dob_gender') }}</td><td>{{ optional($registration->date_of_birth)->format('Y-m-d') ?: '-' }} / {{ $registration->gender ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.nationality') }}</td><td>{{ $registration->nationality }}</td></tr>
                <tr><td>{{ __('admin.passport') }}</td><td>{{ $registration->passport_number }} @if($registration->passport_expiry_date)<br><span class="mini">Expires {{ $registration->passport_expiry_date->format('Y-m-d') }}</span>@endif</td></tr>
                <tr><td>{{ __('admin.email') }}</td><td>{{ $registration->student_email }}</td></tr>
                <tr><td>{{ __('admin.phone') }}</td><td>{{ $registration->student_phone ?: '-' }}</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.school_guardian') }}</h2></div>
            <table>
                <tr><td>{{ __('admin.school') }}</td><td>{{ $registration->school_name }}</td></tr>
                <tr><td>{{ __('admin.location') }}</td><td>{{ collect([$registration->school_city, $registration->school_country])->filter()->implode(', ') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.grade_graduation') }}</td><td>{{ $registration->grade_level }} / {{ $registration->graduation_year ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.parent') }}</td><td>{{ $registration->contact?->parent_full_name }}<br><span class="mini">{{ $registration->contact?->relationship }}</span></td></tr>
                <tr><td>{{ __('admin.parent_email') }}</td><td>{{ $registration->contact?->parent_email }}</td></tr>
                <tr><td>{{ __('admin.parent_phone') }}</td><td>{{ $registration->contact?->parent_phone }}</td></tr>
                <tr><td>{{ __('admin.mailing_address') }}</td><td>{{ collect([$registration->contact?->mailing_address, $registration->contact?->mailing_city, $registration->contact?->postal_code])->filter()->implode(', ') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.emergency') }}</td><td>{{ $registration->contact?->emergency_contact_name }} / {{ $registration->contact?->emergency_contact_phone }}<br><span class="mini">{{ $registration->contact?->emergency_contact_relationship }}</span></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.exam_selection') }}</h2></div>
            <table>
                @foreach($registration->exams as $exam)
                    <tr>
                        <td>{{ $exam->name }}<br><span class="mini">{{ $exam->code }}</span></td>
                        <td>{{ $exam->pivot->exam_date ? \Illuminate\Support\Carbon::parse($exam->pivot->exam_date)->format('Y-m-d') : 'Date TBA' }}<br>Exam NT$ {{ number_format($exam->pivot->exam_fee) }} / Service NT$ {{ number_format($exam->pivot->service_fee) }} / Late NT$ {{ number_format($exam->pivot->late_fee_snapshot) }}</td>
                    </tr>
                @endforeach
                @forelse($registration->practiceExamSelections as $selection)
                    <tr><td>{{ $selection->exam_name }}<br><span class="mini">Practice exam</span></td><td>{{ $selection->currency }} {{ number_format($selection->practice_fee) }}</td></tr>
                @empty
                    <tr><td>Practice exams</td><td>{{ __('admin.none') }}</td></tr>
                @endforelse
                <tr><td>{{ __('admin.grand_total') }}</td><td><strong>{{ $registration->currency }} {{ number_format($registration->grand_total ?: $registration->total_fee) }}</strong></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.accommodations') }}</h2></div>
            <table>
                <tr><td>{{ __('admin.needs_accommodations') }}</td><td>{{ $registration->needs_accommodations ? __('admin.yes') : __('admin.no') }}</td></tr>
                <tr><td>{{ __('admin.ssd_code') }}</td><td>{{ $registration->ssd_code ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.approval_status') }}</td><td>{{ $registration->accommodation_status ?: '-' }}</td></tr>
            </table>
            @if(!empty($registration->accommodations_payload))
                <ul class="list">
                    @foreach($registration->accommodations_payload as $row)
                        <li>{{ $row['exam'] ?? 'Exam not specified' }}: {{ $row['request'] ?? 'Request not specified' }}</li>
                    @endforeach
                </ul>
            @else
                <p class="empty-copy">No accommodation request rows submitted.</p>
            @endif
        </div>

        <div class="card">
            <div class="section-title"><h2>AP Preparation Interest</h2></div>
            <table>
                <tr><td>Interested</td><td>{{ $registration->preparation_interest ? __('admin.yes') : __('admin.no') }}</td></tr>
                <tr><td>Group Class</td><td>{{ $registration->group_class_interest ? __('admin.yes') : __('admin.no') }}</td></tr>
                <tr><td>Private Tutoring</td><td>{{ $registration->private_tutoring_interest ? __('admin.yes') : __('admin.no') }}</td></tr>
                <tr><td>Preferred Schedule</td><td>{{ $registration->preferred_tutoring_schedule ?: '-' }}</td></tr>
                <tr><td>Preferred Language</td><td>{{ $registration->preferred_tutoring_language ?: '-' }}</td></tr>
                <tr><td>Notes</td><td>{{ $registration->preparation_notes ?: '-' }}</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.payment_documents') }}</h2></div>
            <table>
                <tr><td>{{ __('admin.payment_status') }}</td><td>{{ $registration->payment_status }}</td></tr>
                <tr><td>{{ __('admin.amount') }}</td><td>{{ $registration->currency }} {{ number_format($registration->payment_amount ?: ($registration->grand_total ?: $registration->total_fee)) }}</td></tr>
                <tr><td>{{ __('admin.method') }}</td><td>{{ $registration->payment_method }}</td></tr>
                <tr><td>{{ __('admin.reference') }}</td><td>{{ $registration->payment_reference ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.payment_date') }}</td><td>{{ optional($registration->payment_date)->format('Y-m-d') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.passport_status') }}</td><td>{{ $registration->passport_upload_status }}</td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>Submitted Signatures</h2></div>
            <table>
                <tr><td>Student</td><td>{{ $registration->student_signature_name ?: '-' }}<br><span class="mini">{{ optional($registration->student_signature_date)->format('Y-m-d') ?: '-' }}</span></td></tr>
                <tr><td>Parent / Guardian</td><td>{{ $registration->guardian_signature_name ?: '-' }}<br><span class="mini">{{ optional($registration->guardian_signature_date)->format('Y-m-d') ?: '-' }}</span></td></tr>
            </table>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.submission_verification') }}</h2></div>
            <table>
                <tr><td>{{ __('admin.submitted_at') }}</td><td>{{ optional($registration->submitted_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.review_confirmed_at') }}</td><td>{{ optional($registration->review_confirmed_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.confirmation_sent_at') }}</td><td>{{ optional($registration->confirmation_sent_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.verification_status') }}</td><td>{{ $registration->verification_status }}</td></tr>
                <tr><td>{{ __('admin.verified_by') }}</td><td>{{ $registration->verifier?->name ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.verified_at') }}</td><td>{{ optional($registration->verified_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                <tr><td>{{ __('admin.note') }}</td><td>{{ $registration->verification_note ?: '-' }}</td></tr>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="section-title"><h2>{{ __('admin.verification_action') }}</h2></div>
        <form method="POST" action="{{ route('admin.student-registrations.verify',$registration) }}">@csrf
            <label>{{ __('admin.status') }}<select name="verification_status"><option value="verified">Verified</option><option value="needs_review">Needs Review</option><option value="rejected">Rejected</option><option value="unverified">Unverified</option></select></label>
            <label>{{ __('admin.verification_note') }}<textarea name="verification_note"></textarea></label>
            <button class="btn" type="submit">{{ __('admin.update_verification') }}</button>
        </form>
    </div>

    <div class="card">
        <div class="section-title"><h2>{{ __('admin.passport_management') }}</h2></div>
        <div class="grid">
            <div>
                <table>
                    <tr><td>Status</td><td><span class="status">{{ $registration->passport_upload_status }}</span></td></tr>
                    <tr><td>Document UUID</td><td>{{ $registration->passport_document_uuid ?: 'Not assigned' }}</td></tr>
                    <tr><td>File</td><td>{{ $registration->passport_original_name ?: 'No file stored' }}</td></tr>
                    <tr><td>Type / Size</td><td>{{ $registration->passport_mime_type ?: '-' }} / {{ $registration->passport_file_size ? number_format($registration->passport_file_size / 1024, 1).' KB' : '-' }}</td></tr>
                    <tr><td>Uploaded</td><td>{{ optional($registration->passport_uploaded_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                    <tr><td>Viewed / Downloaded</td><td>{{ optional($registration->passport_last_viewed_at)->format('Y-m-d H:i') ?: '-' }} / {{ optional($registration->passport_last_downloaded_at)->format('Y-m-d H:i') ?: '-' }}</td></tr>
                    <tr><td>Verification Note</td><td>{{ $registration->passport_verification_note ?: '-' }}</td></tr>
                    <tr><td>Invalid Reason</td><td>{{ $registration->passport_invalid_reason ?: '-' }}</td></tr>
                    <tr><td>Re-upload Reason</td><td>{{ $registration->passport_reupload_reason ?: '-' }} @if($registration->passport_reupload_deadline_at)<br><span class="mini">Deadline {{ $registration->passport_reupload_deadline_at->format('Y-m-d') }}</span>@endif</td></tr>
                </table>
                @if($registration->passport_file_path)
                    <p class="actions"><a class="btn light" href="{{ route('admin.student-registrations.passport.preview',$registration) }}" target="_blank">Preview</a><a class="btn light" href="{{ route('admin.student-registrations.passport.download',$registration) }}">Download</a></p>
                @endif
            </div>
            <div>
                <form method="POST" enctype="multipart/form-data" action="{{ route('admin.student-registrations.passport.replace',$registration) }}">@csrf<h3>{{ __('admin.replace_passport') }}</h3><label>{{ __('admin.new_file') }}<input type="file" name="passport" accept=".pdf,.jpg,.jpeg,.png" required></label><label>{{ __('admin.reason') }}<textarea name="reason" required></textarea></label><button class="btn" type="submit">{{ __('admin.replace_file') }}</button></form>
                <form method="POST" action="{{ route('admin.student-registrations.passport.status',$registration) }}">@csrf<h3>{{ __('admin.mark_status') }}</h3><label>{{ __('admin.status') }}<select name="status"><option value="verified">Valid</option><option value="invalid">Invalid</option></select></label><label>{{ __('admin.verification_note') }}<textarea name="verification_note"></textarea></label><label>{{ __('admin.invalid_reason') }}<textarea name="invalid_reason"></textarea></label><button class="btn" type="submit">{{ __('admin.update_passport') }}</button></form>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.student-registrations.passport.reupload',$registration) }}">@csrf<h3>{{ __('admin.request_reupload') }}</h3><div class="grid"><label>{{ __('admin.reason') }}<textarea name="reason" required></textarea></label><label>{{ __('admin.deadline') }}<input type="date" name="deadline" required></label></div><button class="btn" type="submit">{{ __('admin.send_reupload_email') }}</button></form>
    </div>

    <div class="grid">
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.internal_notes') }}</h2></div>
            <form method="POST" action="{{ route('admin.student-registrations.notes.store',$registration) }}">@csrf<label>Type<select name="note_type">@foreach(['general','payment','document','student_contact','school_communication','issue','follow_up'] as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select></label><label>{{ __('admin.note') }}<textarea name="note" required></textarea></label><label><span><input type="checkbox" name="is_pinned" value="1"> {{ __('admin.pin_note') }}</span></label><button class="btn" type="submit">{{ __('admin.add_note') }}</button></form>
            @foreach($registration->adminNotes->sortByDesc('created_at') as $note)
                <div class="note"><strong>{{ $note->note_type }}</strong> @if($note->is_pinned)<span>PINNED</span>@endif<br>{{ $note->note }}<br><small>{{ $note->author?->name }} / {{ $note->created_at->format('Y-m-d H:i') }}</small></div>
            @endforeach
        </div>
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.activity_log') }}</h2></div>
            <div class="timeline">
                @forelse($registration->auditLogs->sortByDesc('performed_at') as $log)
                    <div><strong>{{ $log->action }}</strong> {{ $log->field_name }}<br><small>{{ $log->old_value }} -> {{ $log->new_value }}</small><br><small>{{ $log->performed_at->format('Y-m-d H:i') }} / {{ $log->reason }}</small></div>
                @empty
                    @if($registration->histories->isEmpty())
                        <p class="empty-copy">No activity has been recorded yet.</p>
                    @endif
                @endforelse
                @foreach($registration->histories as $history)
                    <div><strong>{{ $history->to_status }}</strong><br>{{ $history->note }}<br><small>{{ $history->created_at->format('Y-m-d H:i') }}</small></div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
</x-admin-shell>
