<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExamRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_register_url_redirects_to_student_registration_flow(): void
    {
        $this->get('/register')
            ->assertRedirect('/student-registration');

        $this->post('/registrations')
            ->assertRedirect('/student-registration');

        $this->get('/registrations/legacy-reference')
            ->assertRedirect('/student-registration');
    }
}
