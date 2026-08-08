<?php

namespace Tests\Feature;

use App\Mail\StudentRegistrationConfirmation;
use App\Models\ApExamSubject;
use App\Models\ExamSeason;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;

class ExamPreferenceSelectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_exam_selection_list_shows_status_date_and_fee_availability(): void
    {
        $subject = $this->subject(['status' => 'limited', 'category' => 'Mathematics']);

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee($subject->name)
            ->assertSee($subject->code)
            ->assertSee('Mathematics')
            ->assertSee('Limited Seats')
            ->assertSee('Unified pricing is based on the total number of AP exams selected.')
            ->assertSee('Coming Soon')
            ->assertDontSee('Exam NT$ 7,800')
            ->assertDontSee('Service NT$ 1,200');
    }

    public function test_uuid_selection_stores_late_fee_and_fee_snapshot(): void
    {
        Mail::fake();
        $subject = $this->subject([
            'late_registration_start_at' => now()->subDay(),
            'late_registration_end_at' => now()->addDay(),
            'late_registration_fee' => 1500,
        ]);

        $this->post('/student-registration', $this->payload([
            'exam_subject_uuids' => [$subject->uuid],
        ]))->assertRedirect();

        $registration = StudentRegistration::query()->with('exams')->firstOrFail();
        $this->assertSame(1500, $registration->late_fee_total);
        $this->assertSame(19000, $registration->total_fee);
        $this->assertSame(1500, (int) $registration->exams->first()->pivot->late_fee_snapshot);
        $this->assertSame(19000, (int) $registration->exams->first()->pivot->total_amount_snapshot);
        Mail::assertSent(StudentRegistrationConfirmation::class);
    }

    public function test_full_or_quota_reached_exam_cannot_be_selected(): void
    {
        $full = $this->subject(['status' => 'full']);
        $quotaReached = $this->subject(['code' => 'QUOTA', 'name' => 'Quota Subject', 'quota' => 1, 'registered_count' => 1]);

        $this->post('/student-registration', $this->payload(['exam_subject_uuids' => [$full->uuid]]))
            ->assertSessionHasErrors(['exam_subject_uuids']);

        $this->post('/student-registration', $this->payload([
            'student_email' => 'quota@example.com',
            'passport_number' => 'Q12345678',
            'exam_subject_uuids' => [$quotaReached->uuid],
        ]))->assertSessionHasErrors(['exam_subject_uuids']);
    }

    public function test_closed_subject_displays_the_real_selection_block_reason(): void
    {
        $subject = $this->subject([
            'registration_open_at' => now()->addWeek(),
            'registration_close_at' => now()->addMonth(),
        ]);

        $this->assertSame('not_yet_open', $subject->selectionBlockReason());

        $this->get('/student-registration')
            ->assertOk()
            ->assertSee($subject->name)
            ->assertSee('Opens '.$subject->registration_open_at->format('M d, Y H:i'))
            ->assertSee('disabled', false);
    }

    public function test_catalog_sync_is_idempotent_and_seeds_three_selectable_categories(): void
    {
        $this->artisan('registration:sync-catalog')->assertSuccessful();

        $this->assertSame(11, ApExamSubject::query()->count());
        $this->assertSame(
            ['General', 'Mathematics', 'Sciences'],
            ApExamSubject::query()->distinct()->orderBy('category')->pluck('category')->all()
        );
        $this->assertSame(11, ApExamSubject::query()->with('examSeason')->get()->filter->isSelectable()->count());
        $this->assertSame([
            'BIO' => ['2027-05-03', '13:00'],
            'CHEM' => ['2027-05-06', '13:00'],
            'PHY1' => ['2027-05-05', '13:00'],
            'CALAB' => ['2027-05-10', '09:00'],
            'CALBC' => ['2027-05-10', '09:00'],
            'STAT' => ['2027-05-11', '13:00'],
            'CSA' => ['2027-05-12', '13:00'],
            'ENGLANG' => ['2027-05-12', '09:00'],
            'MACRO' => ['2027-05-07', '13:00'],
            'PSY' => ['2027-05-14', '13:00'],
            'CHN' => ['2027-05-13', '13:00'],
        ], ApExamSubject::query()->orderBy('sort_order')->get()->mapWithKeys(fn (ApExamSubject $subject) => [
            $subject->code => [$subject->exam_date->toDateString(), substr((string) $subject->start_time, 0, 5)],
        ])->all());
        $this->assertSame(0, ApExamSubject::query()->whereNotNull('end_time')->count());

        $seasonUuid = ExamSeason::query()->where('exam_year', 2027)->value('uuid');
        $subjectUuid = ApExamSubject::query()->where('code', 'CALAB')->value('uuid');

        $this->artisan('registration:sync-catalog')->assertSuccessful();

        $this->assertSame(11, ApExamSubject::query()->count());
        $this->assertSame($seasonUuid, ExamSeason::query()->where('exam_year', 2027)->value('uuid'));
        $this->assertSame($subjectUuid, ApExamSubject::query()->where('code', 'CALAB')->value('uuid'));
    }

    public function test_frontend_fee_tampering_is_ignored(): void
    {
        Mail::fake();
        $subject = $this->subject(['exam_fee' => 7800, 'service_fee' => 1200]);

        $payload = $this->payload([
            'exam_subject_uuids' => [$subject->uuid],
            'exam_fee_total' => 1,
            'total_fee' => 1,
        ]);

        $this->post('/student-registration', $payload)->assertRedirect();

        $this->assertSame(17500, StudentRegistration::query()->firstOrFail()->total_fee);
    }

    public function test_admin_can_create_update_and_delete_exam_subject(): void
    {
        $this->actingAs($this->adminUser());

        $payload = [
            'code' => 'ART',
            'name' => 'Art History',
            'category' => 'Arts',
            'description' => 'AP Art History',
            'exam_date' => '2027-05-20',
            'start_time' => '08:00',
            'timezone' => 'Asia/Taipei',
            'location' => 'Room 101',
            'quota' => 20,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 1500,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subDay()->format('Y-m-d H:i:s'),
            'registration_close_at' => now()->addMonth()->format('Y-m-d H:i:s'),
            'late_registration_start_at' => now()->addWeek()->format('Y-m-d H:i:s'),
            'late_registration_end_at' => now()->addWeeks(2)->format('Y-m-d H:i:s'),
            'sort_order' => 99,
            'is_active' => '1',
        ];

        $this->post(route('admin.ap-exam-subjects.store'), $payload)
            ->assertRedirect(route('admin.ap-exam-subjects.index'));

        $subject = ApExamSubject::query()->where('code', 'ART')->firstOrFail();

        $this->put(route('admin.ap-exam-subjects.update', $subject), array_replace($payload, [
            'status' => 'limited',
            'service_fee' => 1300,
        ]))->assertRedirect(route('admin.ap-exam-subjects.index'));

        $this->assertSame('limited', $subject->fresh()->status);
        $this->assertSame(1300, $subject->fresh()->service_fee);
        $this->assertNull($subject->fresh()->end_time);

        $this->delete(route('admin.ap-exam-subjects.destroy', $subject))
            ->assertRedirect(route('admin.ap-exam-subjects.index'));

        $this->assertSoftDeleted('ap_exam_subjects', ['id' => $subject->id]);
    }

    private function adminUser(): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('StrongPass!123'),
            'is_admin' => true,
        ]);
    }

    private function subject(array $overrides = []): ApExamSubject
    {
        return ApExamSubject::query()->create(array_replace([
            'name' => 'Calculus AB',
            'code' => 'CALAB',
            'category' => 'Mathematics',
            'exam_date' => '2027-05-04',
            'start_time' => '08:00',
            'end_time' => '12:00',
            'timezone' => 'Asia/Taipei',
            'location' => 'TPCA Campus',
            'quota' => 50,
            'registered_count' => 0,
            'exam_fee' => 7800,
            'service_fee' => 1200,
            'late_registration_fee' => 0,
            'currency' => 'NTD',
            'status' => 'open',
            'registration_open_at' => now()->subMonth(),
            'registration_close_at' => now()->addMonth(),
            'sort_order' => 1,
            'is_active' => true,
        ], $overrides));
    }

    private function payload(array $overrides = []): array
    {
        return array_replace([
            'student_full_name' => 'Alex Chen',
            'family_name_en' => 'CHEN',
            'first_name_en' => 'Ming',
            'date_of_birth' => '2009-01-15',
            'nationality' => 'Taiwan',
            'passport_number' => 'A12345678',
            'passport_file' => UploadedFile::fake()->image('passport.jpg'),
            'student_email' => 'alex@example.com',
            'student_phone' => '+886 912 345 678',
            'school_name' => 'Taipei International School',
            'school_country' => 'Taiwan',
            'grade_level' => '11',
            'parent_full_name' => 'Ivon Jou',
            'relationship' => 'Mother',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '+886 987 654 321',
            'emergency_contact_name' => 'Mark Jou',
            'emergency_contact_phone' => '+886 988 111 222',
            'emergency_contact_relationship' => 'Father',
            'payment_method' => 'bank_transfer',
            'accurate_information' => '1',
            'ap_policies' => '1',
            'privacy_policy' => '1',
            'terms_conditions' => '1',
            'confirmed_review' => '1',
            'student_signature_name' => 'Alex Student',
            'student_signature_date' => now()->toDateString(),
            'guardian_signature_name' => 'Pat Parent',
            'guardian_signature_date' => now()->toDateString(),
        ], $overrides);
    }
}
