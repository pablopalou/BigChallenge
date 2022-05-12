<?php

namespace Tests\Feature;

use App\Models\PatientInformation;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteSubmissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_patient_delete_his_her_submission_successfully()
    {
        (new RolesSeeder)->run();
        $submission = Submission::factory()->create();
        $userPatient = $submission->patient;
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);

        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Submission deleted successfully',
        ]);
    }

    public function test_patient_can_not_delete_other_submission()
    {
        (new RolesSeeder)->run();
        $patient = User::factory()->has(PatientInformation::factory())->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        // $doctor = User::factory()->has(DoctorInformation::factory())->create();
        // $doctor->assignRole('doctor');
        // Sanctum::actingAs($doctor);
        $submission = Submission::factory()->create();
        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(403);
    }

    public function test_doctor_can_not_delete_submission()
    {
        (new RolesSeeder)->run();
        $submission = Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $userDoctor = $submission->doctor;
        $userDoctor->assignRole('doctor');
        Sanctum::actingAs($userDoctor);
        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(403);
    }

    public function test_guest_can_not_delete_submission()
    {
        $submission = Submission::factory()->create();
        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(401);
    }

    public function test_wrong_submission_id()
    {
        (new RolesSeeder)->run();
        $submission = Submission::factory()->create();
        $userPatient = $submission->patient;
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        $response = $this->deleteJson("/api/submission/2");
        $response->assertStatus(404);
    }
}
