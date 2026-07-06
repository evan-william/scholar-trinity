<x-admin-shell
    title="System Settings"
    subtitle="General app-level preferences that are not payment, e-invoice, or landing content."
>
    <section class="grid-2">
        <div class="card">
            <div class="section-title"><h2>Save Setting</h2></div>
            <form method="POST" action="{{ route('admin.system-settings.store') }}">
                @csrf
                <div class="grid">
                    <label>Group<input name="group" value="general" required></label>
                    <label>Key<input name="key" placeholder="registration.default_locale" required></label>
                </div>
                <label>Type
                    <select name="type" required>
                        @foreach(['string','boolean','integer','text','json'] as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Value<textarea name="value"></textarea></label>
                <label>Description<textarea name="description"></textarea></label>
                <label style="display:flex;flex-direction:row;align-items:center;gap:8px"><input style="width:auto;min-height:auto" type="checkbox" name="is_public" value="1"> Public/readable by frontend later</label>
                <button class="btn" type="submit">Save Setting</button>
            </form>
        </div>
        <div class="card">
            <div class="section-title"><h2>Suggested Keys</h2></div>
            <ul class="list">
                <li><code>registration.default_locale</code></li>
                <li><code>registration.close_message</code></li>
                <li><code>notifications.admin_email</code></li>
                <li><code>crm.default_counselor_email</code></li>
            </ul>
        </div>
    </section>

    <section class="card">
        <div class="section-title"><h2>Saved Settings</h2></div>
        <table>
            <thead><tr><th>Group</th><th>Key</th><th>Type</th><th>Value</th><th>Public</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($settings as $setting)
                    <tr>
                        <td>{{ $setting->group }}</td>
                        <td>{{ $setting->key }}</td>
                        <td>{{ $setting->type }}</td>
                        <td><pre>{{ $setting->value }}</pre></td>
                        <td>{{ $setting->is_public ? 'yes' : 'no' }}</td>
                        <td><form method="POST" action="{{ route('admin.system-settings.destroy', $setting) }}">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No system settings saved yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-admin-shell>
