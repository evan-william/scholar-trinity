<?php

namespace Tests\Feature;

use App\Models\ExamRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_can_be_submitted(): void
    {
        Storage::fake('local');

        $response = $this->post('/registrations', [
            'registration_round' => 'late',
            'student_family_name' => 'CHEN',
            'student_first_name' => 'MING-HUA',
            'student_middle_initial' => 'A',
            'student_middle_name' => 'ALEX',
            'student_chinese_name' => '陳明華',
            'student_class_name' => 'Alex Chen',
            'grade' => '11',
            'school' => 'TPCA',
            'student_email' => 'student@example.com',
            'student_phone' => '0912345678',
            'passport' => UploadedFile::fake()->image('passport.jpg'),
            'parent_first_name' => 'Ivon',
            'parent_last_name' => 'Jou',
            'parent_email' => 'parent@example.com',
            'parent_phone' => '0987654321',
            'relationship' => 'Mother',
            'address_line_1' => '1 Taipei Road',
            'address_line_2' => 'Da-an',
            'city' => 'Taipei',
            'postal_code' => '106',
            'country' => 'Taiwan',
            'selected_exams' => ['Biology', 'Practice: Biology'],
            'other_exams' => ['AP Art History'],
            'needs_accommodations' => '1',
            'ssd_code' => 'SSD-123',
            'accommodation_status' => 'Already Approved',
            'accommodation_exam' => ['Biology'],
            'accommodation_detail' => ['Extra time'],
            'payment_method' => 'bank_transfer',
            'receipt_type' => 'personal',
            'receipt_email' => 'receipt@example.com',
            'terms' => '1',
        ]);

        $registration = ExamRegistration::firstOrFail();

        $response->assertRedirect(route('registrations.show', $registration));
        $this->assertSame(1, $registration->regular_exam_count);
        $this->assertSame(1, $registration->practice_exam_count);
        $this->assertSame(7800, $registration->exam_fee_total);
        $this->assertSame(1800, $registration->practice_fee_total);
        $this->assertSame(1500, $registration->late_fee_total);
        $this->assertSame(1200, $registration->service_fee_total);
        $this->assertSame(12300, $registration->total_due);
        $this->assertSame('pending', $registration->payment_status);
        Storage::disk('local')->assertExists($registration->passport_path);
    }

    public function test_passport_and_exam_selection_are_required(): void
    {
        $response = $this->post('/registrations', [
            'registration_round' => 'regular',
            'terms' => '1',
        ]);

        $response->assertSessionHasErrors(['passport', 'selected_exams', 'student_family_name']);
    }
}
