<?php

namespace Tests\Feature;

use App\Models\PatientInformation;
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
        $user = User::factory()->create();
        $patient = PatientInformation::factory()->create(['user_id' => $user->id]);
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
        $user = User::factory()->create();
        $patient = PatientInformation::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/api/createSubmission', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('submissions', [
            'symptoms' => 'I have temperature since yeasterday and sore throat',
        ]);
    }
}
