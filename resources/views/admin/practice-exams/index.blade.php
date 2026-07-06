<x-admin-shell
    title="Practice Exam Management"
    subtitle="Manage optional AP practice exam subjects, schedules, fees, and visibility."
>
    <section class="card">
        <div class="section-title"><h2>Add Practice Exam</h2></div>
        <form method="POST" action="{{ route('admin.practice-exams.store') }}">
            @csrf
            <div class="grid">
                <label>Season
                    <select name="exam_season_id">
                        <option value="">No season</option>
                        @foreach($seasons as $season)
                            <option value="{{ $season->id }}">{{ $season->season_name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Name<input name="name" required></label>
                <label>Category<input name="category" placeholder="Science, Math, English"></label>
                <label>Practice Date<input type="date" name="practice_date"></label>
                <label>Start Time<input type="time" name="start_time"></label>
                <label>End Time<input type="time" name="end_time"></label>
                <label>Location<input name="location"></label>
                <label>Fee<input type="number" name="fee" value="{{ config('registration.practice_exam_fee', 1800) }}" min="0" required></label>
                <label>Currency<input name="currency" value="NTD" required></label>
                <label>Sort Order<input type="number" name="sort_order" value="0" min="0" required></label>
            </div>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px"><input style="width:auto;min-height:auto" type="checkbox" name="is_active" value="1" checked> Active</label>
            <button class="btn" type="submit">Add Practice Exam</button>
        </form>
    </section>

    <section class="card">
        <div class="section-title"><h2>Practice Exam Options</h2></div>
        <div style="display:grid;gap:14px">
            @forelse($practiceExams as $option)
                <form method="POST" action="{{ route('admin.practice-exams.update', $option) }}" style="border:1px solid #e5e7eb;border-radius:14px;padding:16px;background:#f8fafc">
                    @csrf
                    @method('PUT')
                    <div class="grid">
                        <label>Name<input name="name" value="{{ $option->name }}" required></label>
                        <label>Category<input name="category" value="{{ $option->category }}" placeholder="Science, Math, English"></label>
                        <label>Season
                            <select name="exam_season_id">
                                <option value="">No season</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}" @selected($option->exam_season_id === $season->id)>{{ $season->season_name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Practice Date<input type="date" name="practice_date" value="{{ optional($option->practice_date)->format('Y-m-d') }}"></label>
                        <label>Start Time<input type="time" name="start_time" value="{{ $option->start_time ? substr($option->start_time, 0, 5) : '' }}"></label>
                        <label>End Time<input type="time" name="end_time" value="{{ $option->end_time ? substr($option->end_time, 0, 5) : '' }}"></label>
                        <label>Location<input name="location" value="{{ $option->location }}"></label>
                        <label>Fee<input type="number" name="fee" value="{{ $option->fee }}" min="0" required></label>
                        <label>Currency<input name="currency" value="{{ $option->currency }}" required></label>
                        <label>Sort Order<input type="number" name="sort_order" value="{{ $option->sort_order }}" min="0" required></label>
                    </div>
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin-top:12px">
                        <label style="display:flex;flex-direction:row;align-items:center;gap:8px;margin:0">
                            <input style="width:auto;min-height:auto" type="checkbox" name="is_active" value="1" @checked($option->is_active)> Active
                        </label>
                        <button class="btn" type="submit">Save Practice Exam</button>
                    </div>
                </form>
            @empty
                <p class="muted">No practice exam options yet. The student form will use fallback defaults until options are added.</p>
            @endforelse
        </div>
        {{ $practiceExams->links() }}
    </section>
</x-admin-shell>
