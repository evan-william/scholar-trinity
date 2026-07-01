<p>Dear {{ $registration->student_full_name }},</p>
<p>A passport re-upload has been requested for registration <strong>{{ $registration->registration_number }}</strong>.</p>
<p><strong>Reason:</strong> {{ $registration->passport_reupload_reason }}</p>
<p><strong>Deadline:</strong> {{ optional($registration->passport_reupload_deadline_at)->format('Y-m-d') }}</p>
<p>Accepted file types: PDF, JPG, JPEG, PNG. Maximum file size: 10MB.</p>
<p>Please contact the AP registration team if you have questions.</p>
