<?php

namespace Tests\Feature;

use App\Models\PatientInformation;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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

        $submissionData = [
            'state' => Submission::STATUS_PENDING,
            'symptoms' => 'I have temperature since yeasterday and sore throat',
            'patient_id' => $patient->user->id
        ];
        $response = $this->postJson('/api/createSubmission', $submissionData);
        $response->assertSuccessful();
        $this->assertDatabaseHas('submissions', $submissionData);
        $response->assertJson(['message' => 'Submission created successfully']);
    }

    public function test_submission_by_a_guest()
    {
        $user = User::factory()->create();
        $patient = PatientInformation::factory()->create(['user_id' => $user->id]);

        $submissionData = [
            'state' => Submission::STATUS_PENDING,
            'symptoms' => 'I have temperature since yeasterday and sore throat',
            'patient_id' => $patient->user->id
        ];
        $response = $this->postJson('/api/createSubmission', $submissionData);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('submissions', $submissionData);
    }


}
