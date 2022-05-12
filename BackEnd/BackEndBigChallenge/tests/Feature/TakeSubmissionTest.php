<?php

namespace Tests\Feature;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TakeSubmissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_doctor_can_take_pending_submission()
    {
        (new RolesSeeder)->run();
        $doctor = User::factory()->has(DoctorInformation::factory())->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        Submission::factory()->create(['state' => Submission::STATUS_PENDING]);
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Doctor took the submission successfully',
        ]);
    }

    public function test_doctor_can_not_take_in_progress_submission()
    {
        (new RolesSeeder)->run();
        $doctor = User::factory()->has(DoctorInformation::factory())->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }
    public function test_doctor_can_not_take_ready_submission()
    {
        (new RolesSeeder)->run();
        $doctor = User::factory()->has(DoctorInformation::factory())->create();
        $doctor->assignRole('doctor');
        Sanctum::actingAs($doctor);
        Submission::factory()->create(['state' => Submission::STATUS_READY]);
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }
    public function test_patient_can_not_take_pending_submission()
    {
        (new RolesSeeder)->run();
        $patient = User::factory()->has(PatientInformation::factory())->create();
        $patient->assignRole('patient');
        Sanctum::actingAs($patient);
        Submission::factory()->create(['state' => Submission::STATUS_PENDING]);
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }

    public function test_guest_can_not_take_pending_submission()
    {
        Submission::factory()->create(['state' => Submission::STATUS_PENDING]);
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(401);
    }
}
