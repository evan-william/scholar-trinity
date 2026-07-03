<x-admin-shell
    title="Export History"
    subtitle="Download generated CSV/XLSX registration exports and review export audit metadata."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Generated Exports</h2>
                <p>Exports expire automatically when configured by the export service.</p>
            </div>
            <a class="btn light" href="{{ route('admin.student-registrations.index') }}">Back to Registrations</a>
        </div>
        <table>
            <thead>
                <tr><th>File</th><th>Template</th><th>Format</th><th>Records</th><th>Created By</th><th>Created</th><th>Expires</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($exports as $export)
                    <tr>
                        <td><strong>{{ $export->file_name }}</strong></td>
                        <td>{{ $export->export_type }}</td>
                        <td>{{ strtoupper($export->export_format) }}</td>
                        <td>{{ $export->record_count }}</td>
                        <td>{{ $export->exporter?->name ?: '-' }}</td>
                        <td>{{ $export->exported_at->format('Y-m-d H:i') }}</td>
                        <td>{{ optional($export->expires_at)->format('Y-m-d H:i') ?: '-' }}</td>
                        <td>
                            @if(! $export->expires_at?->isPast())
                                <a class="btn light" href="{{ route('admin.exports.download', $export) }}">Download</a>
                            @else
                                <span class="status failed">Expired</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="muted">No exports yet.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $exports->links() }}
    </section>
</x-admin-shell>
