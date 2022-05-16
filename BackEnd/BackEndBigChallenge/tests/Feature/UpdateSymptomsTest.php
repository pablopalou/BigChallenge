<?php

namespace Tests\Feature;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\Submission;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateSymptomsTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_symptoms_successfully_by_patient()
    {
        $user = User::factory()->patient()->create();
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $user->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have headache and cough',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertSuccessful();
        $this->assertDatabaseHas('submissions', $newSubmissionInformation);
        $response->assertJson([
            'message' => 'Symptoms updated successfully',
        ]);
    }

    public function test_update_symptoms_successfully_by_doctor()
    {
        $user = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $user->id,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertSuccessful();
        $this->assertDatabaseHas('submissions', $newSubmissionInformation);
        $response->assertJson([
            'message' => 'Symptoms updated successfully',
        ]);
    }

    public function test_update_other_symptoms_by_patient()
    {
        $user = User::factory()->patient()->create();
        Sanctum::actingAs($user);

        $userWithSubmission = User::factory()->patient()->create();

        Submission::factory()->create([
            'patient_id' => $userWithSubmission->id,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have headache and cough',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(403);
    }

    public function test_update_other_symptoms_by_doctor()
    {
        $user = User::factory()->doctor()->patient()->create();

        $userWithSubmission = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $userWithSubmission->id]);

        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $userWithSubmission->id,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(403);
    }

    public function test_update_symptoms_by_guest()
    {
        $user = User::factory()->doctor()->patient()->create();

        Submission::factory()->create([
            'patient_id' => $user->id,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(401);
    }

    public function test_update_symptoms_wrong_data()
    {
        $user = User::factory()->patient()->create();
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $user->id,
        ]);

        $newSubmissionInformation = [
            'symptoms' => '',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(422);
    }
}
