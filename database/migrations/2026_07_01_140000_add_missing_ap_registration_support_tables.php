<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('student_registrations')) {
            Schema::table('student_registrations', function (Blueprint $table) {
                if (! Schema::hasColumn('student_registrations', 'family_name_en')) {
                    $table->string('family_name_en', 80)->nullable()->after('status');
                }
                if (! Schema::hasColumn('student_registrations', 'first_name_en')) {
                    $table->string('first_name_en', 80)->nullable()->after('family_name_en');
                }
                if (! Schema::hasColumn('student_registrations', 'middle_initial')) {
                    $table->string('middle_initial', 8)->nullable()->after('first_name_en');
                }
                if (! Schema::hasColumn('student_registrations', 'middle_name')) {
                    $table->string('middle_name', 80)->nullable()->after('middle_initial');
                }
                if (! Schema::hasColumn('student_registrations', 'chinese_legal_name')) {
                    $table->string('chinese_legal_name', 120)->nullable()->after('middle_name');
                }
                if (! Schema::hasColumn('student_registrations', 'needs_accommodations')) {
                    $table->boolean('needs_accommodations')->default(false)->after('grand_total');
                }
                if (! Schema::hasColumn('student_registrations', 'ssd_code')) {
                    $table->string('ssd_code', 60)->nullable()->after('needs_accommodations');
                }
                if (! Schema::hasColumn('student_registrations', 'accommodation_status')) {
                    $table->string('accommodation_status', 40)->nullable()->after('ssd_code');
                }
                if (! Schema::hasColumn('student_registrations', 'accommodations_payload')) {
                    $table->json('accommodations_payload')->nullable()->after('accommodation_status');
                }
                if (! Schema::hasColumn('student_registrations', 'practice_exam_count')) {
                    $table->unsignedSmallInteger('practice_exam_count')->default(0)->after('accommodations_payload');
                }
                if (! Schema::hasColumn('student_registrations', 'practice_exam_total')) {
                    $table->unsignedInteger('practice_exam_total')->default(0)->after('practice_exam_count');
                }
                if (! Schema::hasColumn('student_registrations', 'review_confirmed_at')) {
                    $table->timestamp('review_confirmed_at')->nullable()->after('submitted_at');
                }
                if (! Schema::hasColumn('student_registrations', 'confirmation_sent_at')) {
                    $table->timestamp('confirmation_sent_at')->nullable()->after('review_confirmed_at');
                }
            });
        }

        if (Schema::hasTable('registration_contacts')) {
            Schema::table('registration_contacts', function (Blueprint $table) {
                if (! Schema::hasColumn('registration_contacts', 'parent_first_name')) {
                    $table->string('parent_first_name', 80)->nullable()->after('student_registration_id');
                }
                if (! Schema::hasColumn('registration_contacts', 'parent_last_name')) {
                    $table->string('parent_last_name', 80)->nullable()->after('parent_first_name');
                }
                if (! Schema::hasColumn('registration_contacts', 'mailing_address')) {
                    $table->string('mailing_address')->nullable()->after('parent_phone');
                }
                if (! Schema::hasColumn('registration_contacts', 'mailing_city')) {
                    $table->string('mailing_city', 100)->nullable()->after('mailing_address');
                }
                if (! Schema::hasColumn('registration_contacts', 'postal_code')) {
                    $table->string('postal_code', 20)->nullable()->after('mailing_city');
                }
            });
        }

        if (! Schema::hasTable('registration_exam_selections')) {
            Schema::create('registration_exam_selections', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
                $table->foreignId('ap_exam_subject_id')->nullable()->constrained()->nullOnDelete();
                $table->string('selection_type', 30)->default('regular');
                $table->string('exam_name');
                $table->string('exam_code')->nullable();
                $table->string('category')->nullable();
                $table->date('exam_date')->nullable();
                $table->unsignedInteger('exam_fee')->default(0);
                $table->unsignedInteger('service_fee')->default(0);
                $table->unsignedInteger('late_fee')->default(0);
                $table->unsignedInteger('practice_fee')->default(0);
                $table->unsignedInteger('total_amount')->default(0);
                $table->string('currency', 8)->default('NTD');
                $table->string('status', 40)->default('selected');
                $table->json('metadata')->nullable();
                $table->timestamp('selected_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['student_registration_id', 'selection_type'], 'reg_exam_sel_reg_type_idx');
                $table->index(['ap_exam_subject_id', 'status'], 'reg_exam_sel_subject_status_idx');
                $table->index(['status', 'selected_at'], 'reg_exam_sel_status_selected_idx');
            });
        }

        if (! Schema::hasTable('registration_documents')) {
            Schema::create('registration_documents', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('student_registration_id')->constrained()->cascadeOnDelete();
                $table->string('document_type', 60)->default('passport');
                $table->string('status', 40)->default('pending_review');
                $table->string('storage_disk', 40)->default('local');
                $table->string('file_path')->nullable();
                $table->string('original_name')->nullable();
                $table->string('mime_type', 120)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->string('document_uuid')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('uploaded_at')->nullable();
                $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('verified_at')->nullable();
                $table->text('verification_note')->nullable();
                $table->foreignId('replaced_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('replaced_at')->nullable();
                $table->text('replacement_reason')->nullable();
                $table->timestamp('reupload_requested_at')->nullable();
                $table->timestamp('reupload_deadline_at')->nullable();
                $table->text('reupload_reason')->nullable();
                $table->foreignId('last_viewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('last_viewed_at')->nullable();
                $table->foreignId('last_downloaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('last_downloaded_at')->nullable();
                $table->softDeletes();
                $table->timestamps();
                $table->index(['student_registration_id', 'document_type'], 'reg_docs_reg_type_idx');
                $table->index(['status', 'uploaded_at'], 'reg_docs_status_uploaded_idx');
                $table->index('document_uuid', 'reg_docs_document_uuid_idx');
            });
        }

        if (! Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->string('module')->index('audit_logs_module_idx');
                $table->string('event_type')->index('audit_logs_event_type_idx');
                $table->string('action');
                $table->nullableMorphs('auditable');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_type')->nullable();
                $table->string('ip_address', 45)->nullable()->index('audit_logs_ip_idx');
                $table->text('user_agent')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->json('metadata')->nullable();
                $table->string('status', 40)->default('success')->index('audit_logs_status_idx');
                $table->timestamp('created_at')->nullable()->index('audit_logs_created_at_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('registration_documents');
        Schema::dropIfExists('registration_exam_selections');

        if (Schema::hasTable('registration_contacts')) {
            Schema::table('registration_contacts', function (Blueprint $table) {
                $columns = [
                    'parent_first_name',
                    'parent_last_name',
                    'mailing_address',
                    'mailing_city',
                    'postal_code',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('registration_contacts', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }

        if (Schema::hasTable('student_registrations')) {
            Schema::table('student_registrations', function (Blueprint $table) {
                $columns = [
                    'family_name_en',
                    'first_name_en',
                    'middle_initial',
                    'middle_name',
                    'chinese_legal_name',
                    'needs_accommodations',
                    'ssd_code',
                    'accommodation_status',
                    'accommodations_payload',
                    'practice_exam_count',
                    'practice_exam_total',
                    'review_confirmed_at',
                    'confirmation_sent_at',
                ];

                foreach ($columns as $column) {
                    if (Schema::hasColumn('student_registrations', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
