<x-admin-shell
    :title="__('admin.audit_detail')"
    :subtitle="$log->event_type"
>
    <div class="card">
        <div class="section-title">
            <h2>{{ $log->event_type }}</h2>
            <a class="btn light" href="{{ route('admin.security.audit.index') }}">{{ __('admin.back') }}</a>
        </div>
        <table>
            <tr><td>{{ __('admin.module') }}</td><td>{{ $log->module }}</td></tr>
            <tr><td>{{ __('admin.actions') }}</td><td>{{ $log->action }}</td></tr>
            <tr><td>{{ __('admin.user') }}</td><td>{{ $log->user_id ?: 'guest' }}</td></tr>
            <tr><td>{{ __('admin.ip') }}</td><td>{{ $log->ip_address }}</td></tr>
            <tr><td>{{ __('admin.status') }}</td><td><span class="status">{{ $log->status }}</span></td></tr>
        </table>
    </div>

    <div class="grid">
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.old_values') }}</h2></div>
            <pre>{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
        </div>
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.new_values') }}</h2></div>
            <pre>{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>

    <div class="card">
        <div class="section-title"><h2>{{ __('admin.metadata') }}</h2></div>
        <pre>{{ json_encode($log->metadata, JSON_PRETTY_PRINT) }}</pre>
    </div>
</x-admin-shell>
