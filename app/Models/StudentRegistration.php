<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentRegistration extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'registration_number',
        'exam_season_id',
        'status',
        'registration_period',
        'registration_period_type',
        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_date',
        'payment_amount',
        'student_full_name',
        'preferred_name',
        'gender',
        'date_of_birth',
        'nationality',
        'passport_number',
        'passport_expiry_date',
        'passport_upload_status',
        'passport_document_uuid',
        'passport_file_path',
        'passport_original_name',
        'passport_mime_type',
        'passport_file_size',
        'passport_uploaded_at',
        'passport_uploaded_by',
        'passport_last_viewed_at',
        'passport_last_viewed_by',
        'passport_last_downloaded_at',
        'passport_last_downloaded_by',
        'passport_replaced_at',
        'passport_replaced_by',
        'passport_replacement_reason',
        'passport_verified_at',
        'passport_verified_by',
        'passport_verification_note',
        'passport_invalid_at',
        'passport_invalid_by',
        'passport_invalid_reason',
        'passport_reupload_requested_at',
        'passport_reupload_deadline_at',
        'passport_reupload_reason',
        'student_email',
        'student_phone',
        'school_name',
        'school_country',
        'school_city',
        'grade_level',
        'graduation_year',
        'exam_fee_total',
        'service_fee_total',
        'late_fee_total',
        'total_fee',
        'grand_total',
        'currency',
        'fee_snapshot_at',
        'verification_status',
        'verified_by',
        'verified_at',
        'verification_note',
        'submitted_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry_date' => 'date',
        'submitted_at' => 'datetime',
        'payment_date' => 'datetime',
        'payment_amount' => 'integer',
        'passport_uploaded_at' => 'datetime',
        'passport_last_viewed_at' => 'datetime',
        'passport_last_downloaded_at' => 'datetime',
        'passport_replaced_at' => 'datetime',
        'passport_verified_at' => 'datetime',
        'passport_invalid_at' => 'datetime',
        'passport_reupload_requested_at' => 'datetime',
        'passport_reupload_deadline_at' => 'datetime',
        'passport_file_size' => 'integer',
        'verified_at' => 'datetime',
        'exam_fee_total' => 'integer',
        'service_fee_total' => 'integer',
        'late_fee_total' => 'integer',
        'total_fee' => 'integer',
        'grand_total' => 'integer',
        'fee_snapshot_at' => 'datetime',
    ];

    public function exams(): BelongsToMany
    {
        return $this->belongsToMany(ApExamSubject::class, 'registration_exam_subjects')
            ->withPivot([
                'uuid',
                'subject_name',
                'exam_date',
                'exam_fee',
                'service_fee',
                'late_fee_snapshot',
                'total_amount_snapshot',
                'currency_snapshot',
                'selected_at',
                'status',
            ])
            ->withTimestamps();
    }

    public function examSeason(): BelongsTo
    {
        return $this->belongsTo(ExamSeason::class, 'exam_season_id');
    }

    public function contact(): HasOne
    {
        return $this->hasOne(RegistrationContact::class);
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(RegistrationAgreement::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(RegistrationStatusHistory::class);
    }

    public function adminNotes(): HasMany
    {
        return $this->hasMany(RegistrationAdminNote::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(RegistrationAuditLog::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(RegistrationPayment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(RegistrationPayment::class)->latestOfMany();
    }

    public function receiptRequests(): HasMany
    {
        return $this->hasMany(ReceiptRequest::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
