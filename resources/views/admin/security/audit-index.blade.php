<x-admin-shell
    :title="__('admin.security_audit')"
    :subtitle="__('admin.security_audit_subtitle')"
>
    <div class="card">
        <form class="filters" method="GET" style="grid-template-columns:repeat(7,1fr) auto">
            <input name="module" value="{{ request('module') }}" placeholder="{{ __('admin.module') }}">
            <input name="event_type" value="{{ request('event_type') }}" placeholder="{{ __('admin.event') }}">
            <input name="user_id" value="{{ request('user_id') }}" placeholder="{{ __('admin.user') }}">
            <input name="ip_address" value="{{ request('ip_address') }}" placeholder="{{ __('admin.ip') }}">
            <select name="status">
                <option value="">{{ __('admin.status') }}</option>
                <option value="success" @selected(request('status') === 'success')>success</option>
                <option value="failed" @selected(request('status') === 'failed')>failed</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
            <input type="date" name="date_to" value="{{ request('date_to') }}">
            <button class="btn" type="submit">{{ __('admin.filter') }}</button>
        </form>
    </div>

    <div class="card">
        <div class="section-title"><h2>{{ __('admin.audit_log') }}</h2></div>
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.date') }}</th>
                    <th>{{ __('admin.user') }}</th>
                    <th>{{ __('admin.module') }}</th>
                    <th>{{ __('admin.event') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                    <th>{{ __('admin.ip') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ optional($log->created_at)->format('Y-m-d H:i:s') }}</td>
                        <td>{{ $log->user_id ?: 'guest' }}</td>
                        <td>{{ $log->module }}</td>
                        <td>{{ $log->event_type }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td><span class="status">{{ $log->status }}</span></td>
                        <td><a class="btn light" href="{{ route('admin.security.audit.show',$log) }}">{{ __('admin.details') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $logs->links() }}
    </div>
</x-admin-shell>
