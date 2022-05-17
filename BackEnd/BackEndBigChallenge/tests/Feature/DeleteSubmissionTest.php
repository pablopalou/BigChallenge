<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_delete_his_her_submission_successfully()
    {
        /** @var Submission $submission */
        $submission = Submission::factory()->create();
        Sanctum::actingAs($submission->patient);

        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Submission deleted successfully',
        ]);
    }

    public function test_patient_can_not_delete_other_submission()
    {
        $patient = User::factory()->patient()->create();
        Sanctum::actingAs($patient);
        $submission = Submission::factory()->create();
        $response = $this->deleteJson("/api/submission/{$submission->id}");
        $response->assertStatus(403);
    }

    public function test_doctor_can_not_delete_submission()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
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
        $submission = Submission::factory()->create();
        Sanctum::actingAs($submission->patient);
        $response = $this->deleteJson('/api/submission/2');
        $response->assertStatus(404);
    }
}
