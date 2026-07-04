<x-admin-shell
    :title="__('admin.edit_registration')"
    :subtitle="__('admin.edit_registration_subtitle')"
>
    <form method="POST" action="{{ route('admin.student-registrations.manage-update',$registration) }}">
        @csrf
        @method('PATCH')
        <div class="card">
            <div class="section-title"><h2>{{ __('admin.edit') }} {{ $registration->registration_number }}</h2></div>
            <p>Reason is required when changing critical fields: passport, email, exam selection, or payment fields.</p>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.student_information') }}</h2></div>
            <div class="grid">
                <label>{{ __('admin.full_name') }}<input name="student_full_name" value="{{ old('student_full_name',$registration->student_full_name) }}" required></label>
                <label>{{ __('admin.preferred_name') }}<input name="preferred_name" value="{{ old('preferred_name',$registration->preferred_name) }}"></label>
                <label>English Family Name<input name="family_name_en" value="{{ old('family_name_en',$registration->family_name_en) }}"></label>
                <label>English First Name<input name="first_name_en" value="{{ old('first_name_en',$registration->first_name_en) }}"></label>
                <label>Middle Initial<input name="middle_initial" value="{{ old('middle_initial',$registration->middle_initial) }}"></label>
                <label>Middle Name<input name="middle_name" value="{{ old('middle_name',$registration->middle_name) }}"></label>
                <label>{{ __('admin.chinese_legal_name') }}<input name="chinese_legal_name" value="{{ old('chinese_legal_name',$registration->chinese_legal_name) }}"></label>
                <label>DOB<input type="date" name="date_of_birth" value="{{ old('date_of_birth',optional($registration->date_of_birth)->format('Y-m-d')) }}" required></label>
                <label>{{ __('admin.nationality') }}<input name="nationality" value="{{ old('nationality',$registration->nationality) }}" required></label>
                <label>{{ __('admin.passport') }}<input name="passport_number" value="{{ old('passport_number',$registration->passport_number) }}" required></label>
                <label>{{ __('admin.email') }}<input type="email" name="student_email" value="{{ old('student_email',$registration->student_email) }}" required></label>
                <label>{{ __('admin.phone') }}<input name="student_phone" value="{{ old('student_phone',$registration->student_phone) }}"></label>
            </div>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.school') }}</h2></div>
            <div class="grid">
                <label>School<input name="school_name" value="{{ old('school_name',$registration->school_name) }}" required></label>
                <label>Country<input name="school_country" value="{{ old('school_country',$registration->school_country) }}" required></label>
                <label>City<input name="school_city" value="{{ old('school_city',$registration->school_city) }}"></label>
                <label>Grade<input name="grade_level" value="{{ old('grade_level',$registration->grade_level) }}" required></label>
            </div>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.parent') }}</h2></div>
            <div class="grid">
                <label>Parent First Name<input name="parent_first_name" value="{{ old('parent_first_name',$registration->contact?->parent_first_name) }}"></label>
                <label>Parent Last Name<input name="parent_last_name" value="{{ old('parent_last_name',$registration->contact?->parent_last_name) }}"></label>
                <label>Parent Name<input name="parent_full_name" value="{{ old('parent_full_name',$registration->contact?->parent_full_name) }}" required></label>
                <label>Relationship<input name="relationship" value="{{ old('relationship',$registration->contact?->relationship) }}" required></label>
                <label>Parent Email<input type="email" name="parent_email" value="{{ old('parent_email',$registration->contact?->parent_email) }}" required></label>
                <label>Parent Phone<input name="parent_phone" value="{{ old('parent_phone',$registration->contact?->parent_phone) }}" required></label>
                <label>Mailing Address<input name="mailing_address" value="{{ old('mailing_address',$registration->contact?->mailing_address) }}"></label>
                <label>Mailing City<input name="mailing_city" value="{{ old('mailing_city',$registration->contact?->mailing_city) }}"></label>
                <label>Postal Code<input name="postal_code" value="{{ old('postal_code',$registration->contact?->postal_code) }}"></label>
                <label>Emergency Name<input name="emergency_contact_name" value="{{ old('emergency_contact_name',$registration->contact?->emergency_contact_name) }}" required></label>
                <label>Emergency Phone<input name="emergency_contact_phone" value="{{ old('emergency_contact_phone',$registration->contact?->emergency_contact_phone) }}" required></label>
                <label>Emergency Relationship<input name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship',$registration->contact?->emergency_contact_relationship) }}" required></label>
            </div>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.accommodations') }}</h2></div>
            <div class="grid">
                <label>Needs Accommodations<select name="needs_accommodations"><option value="0" @selected(!old('needs_accommodations',$registration->needs_accommodations))>No</option><option value="1" @selected(old('needs_accommodations',$registration->needs_accommodations))>Yes</option></select></label>
                <label>SSD Code<input name="ssd_code" value="{{ old('ssd_code',$registration->ssd_code) }}"></label>
                <label>Accommodation Status<select name="accommodation_status"><option value="">None</option>@foreach(['approved','pending','new'] as $status)<option value="{{ $status }}" @selected(old('accommodation_status',$registration->accommodation_status)===$status)>{{ $status }}</option>@endforeach</select></label>
            </div>
            <p class="mini">Detailed accommodation request rows are visible on the detail page and export. Edit row-level details in database/admin enhancement later if needed.</p>
        </div>

        <div class="card">
            <div class="section-title"><h2>{{ __('admin.payment_documents') }}</h2></div>
            <div class="grid">
                <label>Registration Status<select name="status">@foreach($statuses as $status)<option value="{{ $status }}" @selected(old('status',$registration->status)===$status)>{{ $status }}</option>@endforeach</select></label>
                <label>Payment Status<select name="payment_status">@foreach($paymentStatuses as $status)<option value="{{ $status }}" @selected(old('payment_status',$registration->payment_status)===$status)>{{ $status }}</option>@endforeach</select></label>
                <label>Payment Method<input name="payment_method" value="{{ old('payment_method',$registration->payment_method) }}"></label>
                <label>Payment Reference<input name="payment_reference" value="{{ old('payment_reference',$registration->payment_reference) }}"></label>
                <label>Payment Date<input type="date" name="payment_date" value="{{ old('payment_date',optional($registration->payment_date)->format('Y-m-d')) }}"></label>
                <label>Payment Amount<input type="number" name="payment_amount" value="{{ old('payment_amount',$registration->payment_amount) }}"></label>
            </div>
        </div>

        @if(!in_array($registration->payment_status, ['paid','refunded'], true))
            <div class="card">
                <div class="section-title"><h2>{{ __('admin.exam_selection') }}</h2></div>
                <div class="grid">
                    @foreach($subjects as $subject)
                        <label style="flex-direction:row;align-items:center"><input type="checkbox" name="exam_subject_uuids[]" value="{{ $subject->uuid }}" @checked($registration->exams->contains('id',$subject->id))> {{ $subject->name }} ({{ $subject->code }})</label>
                    @endforeach
                </div>
                <p class="mini">Practice exam changes are not editable here yet; use a follow-up admin enhancement if needed.</p>
            </div>
        @endif

        <div class="card">
            <label>{{ __('admin.reason') }}<textarea name="reason">{{ old('reason') }}</textarea></label>
            <button class="btn" type="submit">{{ __('admin.save') }}</button>
            <a class="btn light" href="{{ route('admin.student-registrations.show',$registration) }}">{{ __('admin.cancel') }}</a>
        </div>
    </form>
</x-admin-shell>
