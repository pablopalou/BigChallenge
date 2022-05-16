<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TakeSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_take_pending_submission()
    {
        $doctor = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($doctor);
        Submission::factory()->create();
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Doctor took the submission successfully',
        ]);
    }

    public function test_doctor_can_not_take_in_progress_submission()
    {
        $doctor = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($doctor);
        Submission::factory()->inProgress()->create();
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }

    public function test_doctor_can_not_take_ready_submission()
    {
        $doctor = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($doctor);
        Submission::factory()->ready()->create();
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }

    public function test_patient_can_not_take_pending_submission()
    {
        $patient = User::factory()->patient()->create();
        Sanctum::actingAs($patient);
        Submission::factory()->create();
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(403);
    }

    public function test_guest_can_not_take_pending_submission()
    {
        Submission::factory()->create();
        $response = $this->postJson('/api/submission/1/take');
        $response->assertStatus(401);
    }
}
