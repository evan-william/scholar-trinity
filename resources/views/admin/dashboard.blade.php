<x-admin-shell
    :title="__('admin_dashboard.title')"
    subtitle="Operations overview for AP registrations, payments, documents, receipts, quotas, and revenue."
>
    <form class="card" method="GET">
        <div class="section-title">
            <div>
                <h2>{{ __('admin_dashboard.filters') }}</h2>
                <p>Filter the entire dashboard by registration period, date, status, or subject.</p>
            </div>
            <button class="btn" type="submit">Apply Filters</button>
        </div>
        <div class="filters">
            <select name="period">
                <option value="">All periods</option>
                <option value="main" @selected(($filters['period'] ?? '') === 'main')>Main</option>
                <option value="late" @selected(($filters['period'] ?? '') === 'late')>Late</option>
            </select>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}">
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}">
            <select name="status">
                <option value="">All statuses</option>
                @foreach(['submitted','pending_payment','paid','completed','cancelled','expired'] as $status)
                    <option value="{{ $status }}" @selected(($filters['status'] ?? '') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                @endforeach
            </select>
            <select name="subject_id">
                <option value="">All subjects</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" @selected(($filters['subject_id'] ?? '') == $subject->id)>{{ $subject->name }}</option>
                @endforeach
            </select>
            <a class="btn light" href="{{ route('admin.dashboard') }}">Reset</a>
        </div>
    </form>

    @php($metrics = [
        __('admin_dashboard.total_registrations') => [$totals['registrations'], 'Today '.$totals['today'].' / Week '.$totals['week'].' / Month '.$totals['month']],
        __('admin_dashboard.pending_payment') => [$totals['pending_payment'], 'Submitted or waiting verification'],
        __('admin_dashboard.paid_registrations') => [$totals['paid'], 'Collected NT$ '.number_format($totals['total_revenue'])],
        __('admin_dashboard.incomplete_registrations') => [$totals['incomplete'], 'Draft or missing required items'],
        __('admin_dashboard.late_registrations') => [$totals['late'], 'Late fee NT$ '.number_format($totals['late_fee_revenue'])],
        __('admin_dashboard.total_revenue') => ['NT$ '.number_format($totals['total_revenue']), 'Paid registrations only'],
        __('admin_dashboard.service_fee_revenue') => ['NT$ '.number_format($totals['service_fee_revenue']), 'Fapiao eligible service fee'],
        __('admin_dashboard.passport_pending_review') => [$totals['passport_pending_review'], 'Uploaded passports awaiting review'],
        'Pending Docs' => [$totals['pending_documents'], 'Missing, invalid, or re-upload requested'],
        'Waiting Verification' => [$totals['waiting_verification'], 'Needs admin review before completion'],
        'Receipt Pending' => [$totals['receipt_pending'], 'Fapiao/receipt requests to issue'],
        'Quota Watch' => [$totals['subjects_near_quota'], 'Subjects with 5 or fewer seats left'],
    ])

    <section class="metrics" style="margin-top:14px">
        @foreach($metrics as $title => $data)
            <div class="card metric">
                <span class="label">{{ $title }}</span>
                <strong>{{ $data[0] }}</strong>
                <span>{{ $data[1] }}</span>
            </div>
        @endforeach
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <div class="section-title">
                <div>
                    <h2>Operations Queue</h2>
                    <p>What admins should check next.</p>
                </div>
                <a class="btn light" href="{{ route('admin.student-registrations.index') }}">Open Registrations</a>
            </div>
            <table>
                <thead><tr><th>Queue</th><th>Count</th><th>What to check</th></tr></thead>
                <tbody>
                    @foreach($operations as $item)
                        <tr><td>{{ $item['label'] }}</td><td><strong>{{ $item['count'] }}</strong></td><td>{{ $item['hint'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="card">
            <div class="section-title">
                <div>
                    <h2>Quick Actions</h2>
                    <p>Common management tasks from the Word feature list.</p>
                </div>
            </div>
            <div class="filters" style="grid-template-columns:1fr 1fr">
                <a class="btn" href="{{ route('admin.student-registrations.index') }}">View Registrations</a>
                <a class="btn light" href="{{ route('admin.exports.index') }}">Export Data</a>
                <a class="btn light" href="{{ route('admin.payments.index') }}">Review Payments</a>
                <a class="btn light" href="{{ route('admin.receipts.index') }}">Issue Receipts</a>
                <a class="btn light" href="{{ route('admin.ap-exam-subjects.index') }}">Manage AP Exams</a>
                <a class="btn light" href="{{ route('admin.exam-seasons.index') }}">Registration Periods</a>
            </div>
        </div>
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <div class="section-title">
                <div>
                    <h2>Registrations by Day</h2>
                    <p>Daily submissions for the selected filter window.</p>
                </div>
            </div>
            @php($dayMax = max($byDay ?: [1]))
            <div class="chart">
                @foreach($byDay ?: ['No data' => 0] as $day => $count)
                    <div class="chart-bar" title="{{ $day }}: {{ $count }}" style="height:{{ max(4, ($count / $dayMax) * 140) }}px"></div>
                @endforeach
            </div>
        </div>

        <div class="card">
            <div class="section-title">
                <div>
                    <h2>Payment Status Breakdown</h2>
                    <p>Registration statuses after current filters.</p>
                </div>
            </div>
            <table>
                <tbody>
                    @forelse($byStatus as $status => $count)
                        <tr><td><span class="status {{ $status }}">{{ str_replace('_', ' ', $status) }}</span></td><td><strong>{{ $count }}</strong></td></tr>
                    @empty
                        <tr><td colspan="2" class="muted">No status data yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card" style="margin-top:14px">
        <div class="section-title">
            <div>
                <h2>{{ __('admin_dashboard.exam_subject_summary') }}</h2>
                <p>Quota, remaining seats, and fee totals by AP subject.</p>
            </div>
            <a class="btn light" href="{{ route('admin.ap-exam-subjects.index') }}">Manage Subjects</a>
        </div>
        <table>
            <thead>
                <tr><th>Subject</th><th>Selected</th><th>Quota</th><th>Remaining</th><th>Status</th><th>Exam Fee</th><th>Service Fee</th><th>Late Fee</th></tr>
            </thead>
            <tbody>
                @forelse($bySubject as $row)
                    <tr>
                        <td>{{ $row['name'] }} <span class="muted">({{ $row['code'] }})</span></td>
                        <td>{{ $row['selected_count'] }}</td>
                        <td>{{ $row['quota'] ?? 'Unlimited' }}</td>
                        <td>
                            {{ $row['remaining'] ?? 'Unlimited' }}
                            <div class="bar"><i style="width:{{ $row['quota'] ? min(100, ($row['selected_count'] / $row['quota']) * 100) : 0 }}%"></i></div>
                        </td>
                        <td><span class="status {{ $row['status'] }}">{{ str_replace('_', ' ', $row['status']) }}</span></td>
                        <td>NT$ {{ number_format($row['exam_fee_total']) }}</td>
                        <td>NT$ {{ number_format($row['service_fee_total']) }}</td>
                        <td>NT$ {{ number_format($row['late_fee_total']) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">No subject selections yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-admin-shell>
