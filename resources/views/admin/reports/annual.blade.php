<x-admin-shell
    title="Annual AP Registration Reports"
    subtitle="Season, revenue, subject, payment, and receipt summary for yearly reuse."
>
    @php($currency = $selectedSeason?->currency ?? 'NTD')

    <form class="card" method="GET">
        <div class="section-title">
            <div>
                <h2>Season Report</h2>
                <p>Select an exam season and export the yearly report as CSV.</p>
            </div>
            <div class="top-actions">
                <button class="btn" type="submit">View</button>
                <a class="btn light" href="{{ route('admin.reports.annual.export', ['season' => $selectedSeason?->uuid]) }}">Export CSV</a>
                <a class="btn light" href="{{ route('admin.exam-seasons.index') }}">Seasons</a>
            </div>
        </div>
        <div class="filters" style="grid-template-columns:minmax(220px,360px)">
            <select name="season">
                @foreach($seasons as $season)
                    <option value="{{ $season->uuid }}" @selected($selectedSeason?->id === $season->id)>{{ $season->season_name }} ({{ $season->exam_year }})</option>
                @endforeach
            </select>
        </div>
    </form>

    @php($registrationMetrics = [
        'total' => 'Total registrations',
        'main' => 'Main registrations',
        'late' => 'Late registrations',
        'completed' => 'Completed registrations',
        'pending_payment' => 'Pending payment',
        'cancelled' => 'Cancelled',
        'verified' => 'Verified registrations',
        'needs_accommodations' => 'Accommodation requests',
        'practice_exam_count' => 'Practice exams',
    ])

    <section class="metrics" style="margin-top:14px">
        @foreach($registrationMetrics as $key => $label)
            <div class="card metric">
                <span class="label">{{ $label }}</span>
                <strong>{{ number_format($report['registration'][$key] ?? 0) }}</strong>
                <span>{{ $selectedSeason?->season_name ?: 'All seasons' }}</span>
            </div>
        @endforeach
    </section>

    @php($revenueMetrics = [
        'grand_total' => 'Grand amount',
        'exam_fee' => 'Exam fee',
        'service_fee' => 'Service fee',
        'late_fee' => 'Late fee',
        'practice_exam' => 'Practice exam fee',
        'paid' => 'Paid amount',
        'pending' => 'Pending amount',
        'refunded' => 'Refunded amount',
        'receipt_eligible' => 'Receipt eligible',
    ])

    <section class="metrics" style="margin-top:14px">
        @foreach($revenueMetrics as $key => $label)
            <div class="card metric">
                <span class="label">{{ $label }}</span>
                <strong>{{ $currency }} {{ number_format($report['revenue'][$key] ?? 0) }}</strong>
                <span>Season revenue summary</span>
            </div>
        @endforeach
    </section>

    <section class="card" style="margin-top:14px">
        <div class="section-title">
            <div>
                <h2>Exam Subject Report</h2>
                <p>Quota, registered count, paid count, fee total, and fill rate.</p>
            </div>
        </div>
        <table>
            <thead><tr><th>Subject</th><th>Quota</th><th>Registered</th><th>Remaining</th><th>Paid</th><th>Fee Total</th><th>Fill</th></tr></thead>
            <tbody>
                @forelse($report['subjects'] as $row)
                    @php($subject = $row['subject'])
                    <tr>
                        <td>{{ $subject->code }} - {{ $subject->name }}</td>
                        <td>{{ $subject->quota ?? 'No cap' }}</td>
                        <td>{{ $subject->registered_count }}</td>
                        <td>{{ $row['remaining'] ?? 'No cap' }}</td>
                        <td>{{ $subject->paid_count }}</td>
                        <td>{{ $subject->currency }} {{ number_format(($subject->exam_fee + $subject->service_fee + $subject->late_registration_fee) * $subject->registered_count) }}</td>
                        <td><div class="bar"><i style="width:{{ $subject->quota ? min(100, ($subject->registered_count / max(1, $subject->quota)) * 100) : 0 }}%"></i></div></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="muted">No subjects found for this season.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <section class="grid-2" style="margin-top:14px">
        <div class="card">
            <h2>Payment Status</h2>
            <table>
                <tbody>
                    @forelse($report['payment_statuses'] as $row)
                        <tr><td><span class="status {{ $row->payment_status }}">{{ str_replace('_', ' ', $row->payment_status) }}</span></td><td>{{ $row->total }}</td><td>{{ $currency }} {{ number_format($row->amount) }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="muted">No payment data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <h2>Receipt Status</h2>
            <table>
                <tbody>
                    @forelse($report['receipt_statuses'] as $row)
                        <tr><td><span class="status {{ $row->status }}">{{ str_replace('_', ' ', $row->status) }}</span></td><td>{{ $row->total }}</td><td>{{ $currency }} {{ number_format($row->amount) }}</td></tr>
                    @empty
                        <tr><td colspan="3" class="muted">No receipt data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card" style="margin-top:14px">
        <h2>Registration Trend</h2>
        <table>
            <tbody>
                @forelse($report['trend'] as $row)
                    <tr><td>{{ $row['date'] }}</td><td>{{ $row['total'] }}</td></tr>
                @empty
                    <tr><td class="muted">No submitted registrations in this season.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-admin-shell>
