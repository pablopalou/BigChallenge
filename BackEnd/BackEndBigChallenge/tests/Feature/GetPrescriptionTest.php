<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetPrescriptionTest extends TestCase
{
    use RefreshDatabase;
    public function test_patient_can_get_prescription_file()
    {
        $this->markTestSkipped('This test is skipped');
        $submission = Submission::factory()->ready()->create();
        $this->actingAs($submission->patient);
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'url',
        ]);
    }

    public function test_doctor_can_get_prescription_file()
    {
        $this->markTestSkipped('This test is skipped');
        $submission = Submission::factory()->ready()->create();
        $this->actingAs($submission->doctor);
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'url',
        ]);
    }

    public function test_patient_cannot_get_prescription_file_if_not_ready()
    {
        $submission = Submission::factory()->inProgress()->create();
        $patient = $submission->patient;
        $this->actingAs($patient);
        $response = $this->getJson('api/submission/prescription/' . $submission->id);
        $response->assertForbidden();
    } 

    public function test_doctor_cannot_get_prescription_file_if_not_ready()
    {
        $submission = Submission::factory()->inProgress()->create();
        $doctor = $submission->doctor;
        $this->actingAs($doctor);
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertForbidden();
    }

    public function test_patient_cannot_get_prescription_file_if_not_from_patient()
    {
        $submission = Submission::factory()->ready()->create();
        $patient = User::factory()->patient()->create();
        $this->actingAs($patient);
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertForbidden();
    }

    public function test_doctor_cannot_get_prescription_file_if_not_from_doctor()
    {
        $submission = Submission::factory()->ready()->create();
        $doctor = User::factory()->doctor()->create();
        $this->actingAs($doctor);
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertForbidden();
    }

    public function test_guest_cannot_get_prescription_file()
    {
        $submission = Submission::factory()->ready()->create();
        $response = $this->getJson('api/submission/prescription/'.$submission->id);
        $response->assertUnauthorized();
    }
}
