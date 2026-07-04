@php
    $pageTitle = $season->exists ? __('admin.edit').' '.__('admin.exam_seasons') : __('admin.create_season');
    $isActive = (string) old('is_active', (int) $season->is_active);
@endphp

<x-admin-shell :title="$pageTitle" :subtitle="__('admin.registration_seasons_subtitle')">
    <form method="POST" action="{{ $season->exists ? route('admin.exam-seasons.update',$season) : route('admin.exam-seasons.store') }}">
        @csrf
        @if($season->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="section-title">
                <h2>{{ __('admin.exam_seasons') }}</h2>
                <a class="btn light" href="{{ route('admin.exam-seasons.index') }}">{{ __('admin.back') }}</a>
            </div>
            <div class="grid">
                <label>{{ __('admin.season_name') }}<input name="season_name" value="{{ old('season_name',$season->season_name) }}" required></label>
                <label>{{ __('admin.academic_year') }}<input name="academic_year" value="{{ old('academic_year',$season->academic_year) }}" required></label>
                <label>{{ __('admin.exam_year') }}<input type="number" name="exam_year" value="{{ old('exam_year',$season->exam_year) }}" required></label>
                <label>{{ __('admin.status') }}
                    <select name="status">
                        @foreach(['draft','not_open','open','late_registration','closed','archived'] as $status)
                            <option value="{{ $status }}" @selected(old('status',$season->status)===$status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label>{{ __('admin.main_start') }}<input type="datetime-local" name="main_registration_start_at" value="{{ old('main_registration_start_at', optional($season->main_registration_start_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.main_end') }}<input type="datetime-local" name="main_registration_end_at" value="{{ old('main_registration_end_at', optional($season->main_registration_end_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.late_start') }}<input type="datetime-local" name="late_registration_start_at" value="{{ old('late_registration_start_at', optional($season->late_registration_start_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.late_end') }}<input type="datetime-local" name="late_registration_end_at" value="{{ old('late_registration_end_at', optional($season->late_registration_end_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.timezone') }}<input name="timezone" value="{{ old('timezone',$season->timezone ?: 'Asia/Taipei') }}" required></label>
                <label>{{ __('admin.currency') }}<input name="currency" value="{{ old('currency',$season->currency ?: 'NTD') }}" required></label>
                <label>{{ __('admin.default_service_fee') }}<input type="number" name="default_service_fee" value="{{ old('default_service_fee',$season->default_service_fee ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.default_late_fee') }}<input type="number" name="default_late_fee" value="{{ old('default_late_fee',$season->default_late_fee ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.public_status_message') }}<textarea name="public_status_message">{{ old('public_status_message',$season->public_status_message) }}</textarea></label>
                <label>{{ __('admin.notes') }}<textarea name="notes">{{ old('notes',$season->notes) }}</textarea></label>
                <label>{{ __('admin.close_reason') }}<textarea name="close_reason">{{ old('close_reason',$season->close_reason) }}</textarea></label>
                <label>{{ __('admin.reopen_reason') }}<textarea name="reopen_reason">{{ old('reopen_reason',$season->reopen_reason) }}</textarea></label>
                <label>{{ __('admin.active_season') }}
                    <select name="is_active">
                        <option value="0" @selected($isActive === '0')>{{ __('admin.no') }}</option>
                        <option value="1" @selected($isActive === '1')>{{ __('admin.yes') }}</option>
                    </select>
                </label>
            </div>
            <div class="actions">
                <button class="btn" type="submit">{{ __('admin.save') }}</button>
                <a class="btn light" href="{{ route('admin.exam-seasons.index') }}">{{ __('admin.cancel') }}</a>
            </div>
        </div>
    </form>
</x-admin-shell>
