<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateSubmissionTest extends TestCase
{
    use RefreshDatabase;

    //Remember that a doctor can also be a patient
    public function test_submission_created_by_patient_successfully()
    {
        $user = User::factory()->patient()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/createSubmission', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas('submissions', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
        $response->assertJson(['message' => 'Submission created successfully']);
    }

    public function test_submission_by_a_guest()
    {
        User::factory()->patient()->create();

        $response = $this->postJson('/api/createSubmission', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('submissions', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
    }
}
