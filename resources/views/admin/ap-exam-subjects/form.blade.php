@php
    $pageTitle = $subject->exists ? __('admin.edit').' '.__('admin.exam_subject') : __('admin.add').' '.__('admin.exam_subject');
    $isActive = (string) old('is_active', (int) $subject->is_active);
@endphp

<x-admin-shell :title="$pageTitle" :subtitle="__('admin.exam_management_subtitle')">
    <form method="POST" action="{{ $subject->exists ? route('admin.ap-exam-subjects.update',$subject) : route('admin.ap-exam-subjects.store') }}">
        @csrf
        @if($subject->exists)
            @method('PUT')
        @endif

        <div class="card">
            <div class="section-title">
                <h2>{{ __('admin.exam_subject') }}</h2>
                <a class="btn light" href="{{ route('admin.ap-exam-subjects.index') }}">{{ __('admin.back') }}</a>
            </div>
            <div class="grid">
                <label>{{ __('admin.exam_seasons') }}
                    <select name="exam_season_id">
                        <option value="">{{ __('admin.legacy') }}</option>
                        @foreach($seasons ?? [] as $season)
                            <option value="{{ $season->id }}" @selected((string) old('exam_season_id',$subject->exam_season_id)===(string) $season->id)>{{ $season->season_name }} ({{ $season->exam_year }})</option>
                        @endforeach
                    </select>
                </label>
                <label>{{ __('admin.exam_code') }}<input name="code" value="{{ old('code',$subject->code) }}" required></label>
                <label>{{ __('admin.exam_name') }}<input name="name" value="{{ old('name',$subject->name) }}" required></label>
                <label>{{ __('admin.category') }}<input name="category" value="{{ old('category',$subject->category) }}" required></label>
                <label>{{ __('admin.status') }}
                    <select name="status">
                        @foreach(['draft','not_open','open','limited','full','closed','cancelled','disabled'] as $status)
                            <option value="{{ $status }}" @selected(old('status',$subject->status)===$status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </label>
                <label>{{ __('admin.description') }}<textarea name="description">{{ old('description',$subject->description) }}</textarea></label>
                <label>{{ __('admin.location') }}<input name="location" value="{{ old('location',$subject->location) }}"></label>
                <label>{{ __('admin.exam_date') }}<input type="date" name="exam_date" value="{{ old('exam_date', optional($subject->exam_date)->format('Y-m-d')) }}"></label>
                <label>{{ __('admin.timezone') }}<input name="timezone" value="{{ old('timezone',$subject->timezone ?: 'Asia/Taipei') }}" required></label>
                <label>{{ __('admin.start_time') }}<input type="time" name="start_time" value="{{ old('start_time', $subject->start_time ? substr($subject->start_time,0,5) : '') }}"></label>
                <label>{{ __('admin.end_time') }}<input type="time" name="end_time" value="{{ old('end_time', $subject->end_time ? substr($subject->end_time,0,5) : '') }}"></label>
                <label>{{ __('admin.quota') }}<input type="number" name="quota" value="{{ old('quota',$subject->quota) }}" min="0"></label>
                <label>{{ __('admin.sort_order') }}<input type="number" name="sort_order" value="{{ old('sort_order',$subject->sort_order ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.exam_fee') }}<input type="number" name="exam_fee" value="{{ old('exam_fee',$subject->exam_fee ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.service_fee') }}<input type="number" name="service_fee" value="{{ old('service_fee',$subject->service_fee ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.late_registration_fee') }}<input type="number" name="late_registration_fee" value="{{ old('late_registration_fee',$subject->late_registration_fee ?? 0) }}" required min="0"></label>
                <label>{{ __('admin.currency') }}<input name="currency" value="{{ old('currency',$subject->currency ?: 'NTD') }}" required></label>
                <label>{{ __('admin.registration_open') }}<input type="datetime-local" name="registration_open_at" value="{{ old('registration_open_at', optional($subject->registration_open_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.registration_close') }}<input type="datetime-local" name="registration_close_at" value="{{ old('registration_close_at', optional($subject->registration_close_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.late_start') }}<input type="datetime-local" name="late_registration_start_at" value="{{ old('late_registration_start_at', optional($subject->late_registration_start_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.late_end') }}<input type="datetime-local" name="late_registration_end_at" value="{{ old('late_registration_end_at', optional($subject->late_registration_end_at)->format('Y-m-d\TH:i')) }}"></label>
                <label>{{ __('admin.active_subject') }}
                    <select name="is_active">
                        <option value="1" @selected($isActive === '1')>{{ __('admin.yes') }}</option>
                        <option value="0" @selected($isActive === '0')>{{ __('admin.no') }}</option>
                    </select>
                </label>
            </div>
            <div class="actions">
                <button class="btn" type="submit">{{ __('admin.save') }}</button>
                <a class="btn light" href="{{ route('admin.ap-exam-subjects.index') }}">{{ __('admin.cancel') }}</a>
            </div>
        </div>
    </form>
</x-admin-shell>
