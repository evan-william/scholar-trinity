<x-admin-shell
    :title="__('admin.registration_seasons')"
    :subtitle="__('admin.registration_seasons_subtitle')"
>
    <div class="card">
        <div class="section-title">
            <h2>{{ __('admin.exam_seasons') }}</h2>
            <div class="actions">
                <a class="btn light" href="{{ route('admin.reports.annual') }}">{{ __('admin.annual_report') }}</a>
                <a class="btn" href="{{ route('admin.exam-seasons.create') }}">{{ __('admin.create_season') }}</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.season') }}</th>
                    <th>{{ __('admin.period') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.subjects') }}</th>
                    <th>{{ __('admin.registrations') }}</th>
                    <th>{{ __('admin.duplicate') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($seasons as $season)
                    <tr>
                        <td>
                            <strong>{{ $season->season_name }}</strong><br>
                            <span class="mini">{{ $season->academic_year }} / {{ $season->exam_year }} {{ $season->is_active ? __('admin.active') : '' }}</span>
                        </td>
                        <td>
                            Main: {{ optional($season->main_registration_start_at)->format('Y-m-d H:i') ?? '-' }} to {{ optional($season->main_registration_end_at)->format('Y-m-d H:i') ?? '-' }}<br>
                            Late: {{ optional($season->late_registration_start_at)->format('Y-m-d H:i') ?? '-' }} to {{ optional($season->late_registration_end_at)->format('Y-m-d H:i') ?? '-' }}<br>
                            <span class="mini">{{ $season->timezone }}</span>
                        </td>
                        <td><span class="status">{{ $season->publicStatus() }}</span><br><span class="mini">{{ $season->status }}</span></td>
                        <td>{{ $season->subjects_count }}</td>
                        <td>{{ $season->registrations_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.exam-seasons.duplicate',$season) }}">
                                @csrf
                                <div class="grid-3">
                                    <input class="compact-input" name="season_name" value="AP Exam {{ $season->exam_year + 1 }}">
                                    <input class="compact-input" name="academic_year" value="{{ $season->exam_year }}-{{ $season->exam_year + 1 }}">
                                    <input class="compact-input" name="exam_year" value="{{ $season->exam_year + 1 }}" type="number">
                                </div>
                                <button class="btn light" type="submit">{{ __('admin.duplicate') }}</button>
                            </form>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="btn light" href="{{ route('admin.exam-seasons.edit',$season) }}">{{ __('admin.edit') }}</a>
                                <form method="POST" action="{{ route('admin.exam-seasons.activate',$season) }}">
                                    @csrf
                                    <button class="btn" type="submit">{{ __('admin.set_active') }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.exam-seasons.archive',$season) }}">
                                    @csrf
                                    <input class="compact-input" name="close_reason" placeholder="{{ __('admin.archive_reason') }}">
                                    <button class="btn danger" type="submit">{{ __('admin.archive') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $seasons->links() }}
    </div>
</x-admin-shell>
