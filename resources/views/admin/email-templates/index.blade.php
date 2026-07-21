<x-admin-shell
    title="Email Templates"
    subtitle="Manage editable subject/body overrides for operational emails."
>
    <section class="grid-2">
        <div class="card">
            <div class="section-title"><h2>Save Template</h2></div>
            <form method="POST" action="{{ route('admin.email-templates.store') }}">
                @csrf
                <label>Template Key
                    <select name="template_key" required>
                        @foreach($templateKeys as $key)
                            <option value="{{ $key }}">{{ $key }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Locale
                    <select name="locale" required>
                        <option value="en">en</option>
                        <option value="zh_TW">zh_TW</option>
                    </select>
                </label>
                <label>Subject<input name="subject" required></label>
                <label>HTML Body<textarea name="body_html" required></textarea></label>
                <label>Text Body<textarea name="body_text"></textarea></label>
                <label style="display:flex;flex-direction:row;align-items:center;gap:8px"><input style="width:auto;min-height:auto" type="checkbox" name="is_active" value="1" checked> Active</label>
                <button class="btn" type="submit">Save Template</button>
            </form>
        </div>
        <div class="card">
            <div class="section-title"><h2>Template Notes</h2></div>
            <p class="muted">Active registration-confirmation templates are rendered immediately. Supported placeholders are replaced safely before the email is sent.</p>
            <ul class="list">
                <li>Available confirmation placeholders: <code>{{ '{{ registration_number }}' }}</code>, <code>{{ '{{ student_name }}' }}</code>, <code>{{ '{{ submitted_at }}' }}</code>, and <code>{{ '{{ selected_exams }}' }}</code>.</li>
                <li>Keep payment, receipt, and passport emails bilingual until final copy is approved.</li>
            </ul>
        </div>
    </section>

    <section class="card">
        <div class="section-title"><h2>Saved Templates</h2></div>
        <table>
            <thead><tr><th>Key</th><th>Locale</th><th>Subject</th><th>Status</th><th>Updated</th><th>Action</th></tr></thead>
            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>{{ $template->template_key }}</td>
                        <td>{{ $template->locale }}</td>
                        <td>{{ $template->subject }}</td>
                        <td><span class="status">{{ $template->is_active ? 'active' : 'inactive' }}</span></td>
                        <td>{{ $template->updated_at->format('Y-m-d H:i') }}</td>
                        <td><form method="POST" action="{{ route('admin.email-templates.destroy', $template) }}">@csrf @method('DELETE')<button class="btn danger" type="submit">Delete</button></form></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No template overrides saved yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-admin-shell>
