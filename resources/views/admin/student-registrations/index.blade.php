<x-admin-shell
    title="Registration Management"
    subtitle="Search, filter, verify, edit, note, export, and audit student registrations."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Registration Filters</h2>
                <p>Find students by name, email, passport, reference, subject, document status, payment status, or registration period.</p>
            </div>
            <button class="btn" form="registrationFilter" type="submit">Filter</button>
        </div>
        <form id="registrationFilter" class="filters" method="GET" style="grid-template-columns:1.5fr repeat(5,minmax(0,1fr))">
            <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, parent email, passport, reference">
            <select name="season_id">
                <option value="">All seasons</option>
                @foreach($seasons as $season)
                    <option value="{{ $season->id }}" @selected(($filters['season_id'] ?? '') == $season->id)>{{ $season->season_name }}</option>
                @endforeach
            </select>
            <select name="subject_id">
                <option value="">All subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(($filters['subject_id'] ?? '') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
            <select name="payment_status">
                <option value="">Payment</option>
                @foreach(['unpaid','pending_payment','waiting_verification','paid','failed','refunded','cancelled'] as $status)
                    <option value="{{ $status }}" @selected(($filters['payment_status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="status">
                <option value="">Registration</option>
                @foreach(['submitted','pending_payment','paid','completed','cancelled','expired','draft'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="document_status">
                <option value="">Document</option>
                @foreach(['missing','uploaded','pending_review','verified','invalid','reupload_requested'] as $status)
                    <option value="{{ $status }}" @selected(($filters['document_status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="verification_status">
                <option value="">Verification</option>
                @foreach(['unverified','needs_review','verified','rejected'] as $status)
                    <option value="{{ $status }}" @selected(($filters['verification_status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="receipt_status">
                <option value="">Receipt</option>
                @foreach(['pending_issue','issued','sent','failed','not_requested'] as $status)
                    <option value="{{ $status }}" @selected(($filters['receipt_status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="needs_accommodations">
                <option value="">Accommodations</option>
                <option value="1" @selected(($filters['needs_accommodations'] ?? '') === '1')>Requested</option>
                <option value="0" @selected(($filters['needs_accommodations'] ?? '') === '0')>Not requested</option>
            </select>
            <select name="accommodation_status">
                <option value="">Accommodation Status</option>
                @foreach(['approved','pending','new'] as $status)
                    <option value="{{ $status }}" @selected(($filters['accommodation_status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="period">
                <option value="">All periods</option>
                <option value="main" @selected(($filters['period'] ?? '') === 'main')>Main</option>
                <option value="late" @selected(($filters['period'] ?? '') === 'late')>Late</option>
            </select>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            <a class="btn light" href="{{ route('admin.student-registrations.index') }}">Reset</a>
        </form>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Export Data</h2>
                <p>Create CSV/XLSX exports for standard, TPCA, or school templates.</p>
            </div>
            <a class="btn light" href="{{ route('admin.exports.index') }}">Export History</a>
        </div>
        <form method="GET" action="{{ route('admin.student-registrations.export') }}">
            @foreach(request()->except(['format','template','include_notes','mask_passport','school']) as $key => $value)
                @if(is_scalar($value))<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endif
            @endforeach
            <div class="filters" style="grid-template-columns:repeat(4,minmax(0,1fr))">
                <label>Format
                    <select name="format">
                        <option value="csv">CSV</option>
                        <option value="xlsx">Excel (.xlsx)</option>
                    </select>
                </label>
                <label>Template
                    <select name="template">
                        <option value="standard">Standard</option>
                        <option value="tpca">TPCA</option>
                        <option value="school">School</option>
                    </select>
                </label>
                <label>Document Status
                    <select name="document_status">
                        <option value="">Any</option>
                        @foreach(['not_uploaded','uploaded','pending_review','verified','invalid','reupload_requested','replaced','deleted'] as $status)
                            <option value="{{ $status }}">{{ str_replace('_', ' ', $status) }}</option>
                        @endforeach
                    </select>
                </label>
                <label>School
                    <input name="school" value="{{ request('school') }}">
                </label>
            </div>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="include_notes" value="1"> Include internal notes
            </label>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="mask_passport" value="1" checked> Mask passport numbers
            </label>
            <button class="btn" type="submit">Create Export</button>
        </form>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Registrations</h2>
                <p>{{ $registrations->total() }} result(s)</p>
            </div>
        </div>
        <table>
            <thead>
                <tr><th>Reference</th><th>Season</th><th>Student</th><th>Parent</th><th>Passport</th><th>School</th><th>Exams</th><th>Period</th><th>Payment</th><th>Registration</th><th>Submitted</th><th>Updated</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @forelse($registrations as $registration)
                    <tr>
                        <td><strong>{{ $registration->registration_number }}</strong></td>
                        <td>{{ $registration->examSeason?->season_name ?? 'Legacy' }}</td>
                        <td>{{ $registration->student_full_name }}<br><span class="muted">{{ $registration->student_email }}</span></td>
                        <td>{{ $registration->contact?->parent_full_name ?: '-' }}<br><span class="muted">{{ $registration->contact?->parent_email ?: '-' }}</span></td>
                        <td>{{ $registration->passport_number ?: '-' }}</td>
                        <td>{{ $registration->school_name }}<br><span class="muted">Grade {{ $registration->grade_level }}</span></td>
                        <td>{{ $registration->exams_count }}</td>
                        <td>{{ $registration->registration_period_type ? ucfirst($registration->registration_period_type) : ($registration->late_fee_total > 0 ? 'Late' : 'Main') }}</td>
                        <td><span class="status {{ $registration->payment_status }}">{{ str_replace('_', ' ', $registration->payment_status) }}</span></td>
                        <td><span class="status {{ $registration->status }}">{{ str_replace('_', ' ', $registration->status) }}</span></td>
                        <td>{{ optional($registration->submitted_at)->format('Y-m-d') ?: '-' }}</td>
                        <td>{{ $registration->updated_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="top-actions" style="justify-content:flex-start">
                                <a class="btn light" href="{{ route('admin.student-registrations.show', $registration) }}">View</a>
                                <a class="btn light" href="{{ route('admin.student-registrations.edit', $registration) }}">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="13" class="muted">No registrations found.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $registrations->links() }}
    </section>
</x-admin-shell>
