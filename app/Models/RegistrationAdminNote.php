<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class RegistrationAdminNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'student_registration_id',
        'note_type',
        'note',
        'is_pinned',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (RegistrationAdminNote $note): void {
            $note->uuid ??= (string) Str::uuid();
        });
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(StudentRegistration::class, 'student_registration_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
