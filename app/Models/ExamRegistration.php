<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamRegistration extends Model
{
    protected $fillable = [
        'reference_number',
        'registration_round',
        'student_family_name',
        'student_first_name',
        'student_middle_initial',
        'student_middle_name',
        'student_chinese_name',
        'student_class_name',
        'grade',
        'school',
        'student_email',
        'student_phone',
        'passport_path',
        'passport_original_name',
        'passport_mime_type',
        'passport_size',
        'parent_first_name',
        'parent_last_name',
        'parent_email',
        'parent_phone',
        'relationship',
        'address_line_1',
        'address_line_2',
        'city',
        'postal_code',
        'country',
        'selected_exams',
        'other_exams',
        'regular_exam_count',
        'practice_exam_count',
        'exam_fee_total',
        'practice_fee_total',
        'late_fee_total',
        'service_fee_total',
        'total_due',
        'needs_accommodations',
        'ssd_code',
        'accommodation_status',
        'accommodations',
        'payment_method',
        'receipt_type',
        'receipt_title',
        'receipt_tax_id',
        'receipt_email',
        'terms_accepted_at',
        'payment_status',
    ];

    protected $casts = [
        'selected_exams' => 'array',
        'other_exams' => 'array',
        'accommodations' => 'array',
        'needs_accommodations' => 'boolean',
        'terms_accepted_at' => 'datetime',
    ];
}
