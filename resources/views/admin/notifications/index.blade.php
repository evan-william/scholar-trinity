<x-admin-shell
    title="Admin Notifications"
    subtitle="Important registration, payment, passport, and receipt events for follow-up."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Notification Queue</h2>
                <p>{{ $unreadCount }} unread notification(s)</p>
            </div>
            <div class="actions">
                <a class="btn light" href="{{ route('admin.notifications.index', ['status' => 'unread']) }}">Unread</a>
                <a class="btn light" href="{{ route('admin.notifications.index') }}">All</a>
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">@csrf<button class="btn" type="submit">Mark All Read</button></form>
            </div>
        </div>
        <table data-datatable="true" data-table-label="Admin notifications">
            <thead><tr><th>Status</th><th>Type</th><th>Message</th><th>Linked Record</th><th>Created</th><th data-sortable="false">Actions</th></tr></thead>
            <tbody>
                @forelse($notifications as $notification)
                    <tr>
                        <td><span class="status {{ $notification->read_at ? 'read' : 'unread' }}">{{ $notification->read_at ? 'read' : 'unread' }}</span></td>
                        <td>{{ str($notification->type)->replace('_', ' ')->title() }}<br><span class="mini">{{ str($notification->severity)->title() }}</span></td>
                        <td><strong>{{ $notification->title }}</strong><br><span class="muted">{{ $notification->body ?: '-' }}</span></td>
                        <td>
                            @if($notification->registration)
                                <a href="{{ route('admin.student-registrations.show', $notification->registration) }}">{{ $notification->registration->registration_number }}</a>
                            @elseif($notification->payment)
                                <a href="{{ route('admin.payments.show', $notification->payment) }}">{{ $notification->payment->payment_reference }}</a>
                            @elseif($notification->link_url)
                                <a href="{{ $notification->link_url }}">Open</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $notification->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($notification->link_url || ! $notification->read_at)
                                <x-admin-actions>
                                    @if($notification->link_url)<a class="btn light" href="{{ $notification->link_url }}">Open</a>@endif
                                    @unless($notification->read_at)
                                        <form method="POST" action="{{ route('admin.notifications.read', $notification) }}">@csrf<button class="btn light" type="submit">Mark Read</button></form>
                                    @endunless
                                </x-admin-actions>
                            @else
                                <span class="muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No notifications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $notifications->links() }}
    </section>
</x-admin-shell>
