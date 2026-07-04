<x-admin-shell
    :title="__('admin.exam_management')"
    :subtitle="__('admin.exam_management_subtitle')"
>
    <div class="card">
        <div class="section-title">
            <h2>{{ __('admin.ap_exam_subjects') }}</h2>
            <a class="btn" href="{{ route('admin.ap-exam-subjects.create') }}">{{ __('admin.add_subject') }}</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.order') }}</th>
                    <th>{{ __('admin.season') }}</th>
                    <th>{{ __('admin.code') }}</th>
                    <th>{{ __('admin.name') }}</th>
                    <th>{{ __('admin.category') }}</th>
                    <th>{{ __('admin.date_time') }}</th>
                    <th>{{ __('admin.quota') }}</th>
                    <th>{{ __('admin.fees') }}</th>
                    <th>{{ __('admin.status') }}</th>
                    <th>{{ __('admin.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    <tr>
                        <td>{{ $subject->sort_order }}</td>
                        <td>{{ $subject->examSeason?->season_name ?? __('admin.legacy') }}</td>
                        <td>{{ $subject->code }}</td>
                        <td>{{ $subject->name }}</td>
                        <td>{{ $subject->category }}</td>
                        <td>{{ optional($subject->exam_date)->format('Y-m-d') }} {{ $subject->start_time ? substr($subject->start_time,0,5) : '' }}-{{ $subject->end_time ? substr($subject->end_time,0,5) : '' }}</td>
                        <td>{{ $subject->registered_count }}/{{ $subject->quota ?? 'No cap' }}</td>
                        <td>{{ $subject->currency }} {{ number_format($subject->exam_fee + $subject->service_fee + $subject->late_registration_fee) }}</td>
                        <td><span class="status">{{ $subject->status }}</span></td>
                        <td>
                            <div class="actions">
                                <a class="btn light" href="{{ route('admin.ap-exam-subjects.edit',$subject) }}">{{ __('admin.edit') }}</a>
                                <form method="POST" action="{{ route('admin.ap-exam-subjects.destroy',$subject) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">{{ __('admin.disable') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $subjects->links() }}
    </div>
</x-admin-shell>
