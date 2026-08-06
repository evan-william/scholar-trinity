<x-admin-shell
    :title="__('admin.exam_management')"
    :subtitle="__('admin.exam_management_subtitle')"
>
    <div class="card">
        <div class="section-title">
            <h2>{{ __('admin.ap_exam_subjects') }}</h2>
            <a class="btn" href="{{ route('admin.ap-exam-subjects.create') }}">{{ __('admin.add_subject') }}</a>
        </div>

        <table data-datatable="true" data-table-label="{{ __('admin.ap_exam_subjects') }}">
            <thead>
                <tr>
                    <th class="is-centered">{{ __('admin.order') }}</th>
                    <th>{{ __('admin.season') }}</th>
                    <th>{{ __('admin.code') }}</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.category') }}</th>
                    <th>{{ __('admin.date_time') }}</th>
                    <th class="is-centered">{{ __('admin.quota') }}</th>
                    <th class="is-centered">{{ __('admin.fees') }}</th>
                    <th class="is-centered">{{ __('admin.status') }}</th>
                    <th class="is-centered">{{ __('admin.registration_availability') }}</th>
                    <th class="is-centered" data-sortable="false">{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr>
                        <td class="is-centered">{{ $subject->sort_order }}</td>
                        <td>{{ $subject->examSeason?->season_name ?? __('admin.legacy') }}</td>
                        <td>{{ $subject->code }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->category }}</td>
                        <td>{{ optional($subject->exam_date)->format('Y-m-d') }}{{ $subject->start_time ? ' '.substr($subject->start_time, 0, 5) : '' }}</td>
                        <td class="is-centered">{{ $subject->registered_count }}/{{ $subject->quota ?? 'No cap' }}</td>
                        <td class="is-centered">{{ $subject->currency }} {{ number_format($subject->exam_fee + $subject->service_fee + $subject->late_registration_fee) }}</td>
                        <td class="is-centered"><span class="status {{ $subject->status }}">{{ $subject->status }}</span></td>
                        <td class="is-centered">
                            @php($blockReason = $subject->selectionBlockReason())
                            <span class="status {{ $blockReason ?: 'selectable_now' }}">{{ $blockReason ? __('student_registration.availability.'.$blockReason, [
                                'date' => $blockReason === 'not_yet_open' ? optional($subject->registration_open_at)->format('Y-m-d H:i') : optional($subject->registration_close_at)->format('Y-m-d H:i'),
                            ]) : __('admin.selectable_now') }}</span>
                        </td>
                        <td class="is-centered">
                            <x-admin-actions>
                                <a class="btn light" href="{{ route('admin.ap-exam-subjects.edit',$subject) }}">{{ __('admin.edit') }}</a>
                                <form method="POST" action="{{ route('admin.ap-exam-subjects.destroy',$subject) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">{{ __('admin.disable') }}</button>
                                </form>
                            </x-admin-actions>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-admin-shell>
