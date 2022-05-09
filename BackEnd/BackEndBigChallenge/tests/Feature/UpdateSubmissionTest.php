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

class UpdateSubmissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_update_submission_successfully_by_patient()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('patient');
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
            'message' => 'Submission updated successfully',
        ]);
    }

    public function test_update_submission_successfully_by_doctor()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('doctor');
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $user->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertSuccessful();
        $this->assertDatabaseHas('submissions', $newSubmissionInformation);
        $response->assertJson([
            'message' => 'Submission updated successfully',
        ]);
    }

    public function test_update_other_submission_by_patient()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('patient');
        Sanctum::actingAs($user);

        $userWithSubmission = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $userWithSubmission->id]);

        Submission::factory()->create([
            'patient_id' => $userWithSubmission->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have headache and cough',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(403);
    }

    public function test_update_other_submission_by_doctor()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('doctor');

        $userWithSubmission = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $userWithSubmission->id]);
        
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $userWithSubmission->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(403);
    }

    public function test_update_submission_by_guest()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('doctor');

        Submission::factory()->create([
            'patient_id' => $user->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => 'i have constant headaches throughout the day',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(401);
    }

    public function test_update_submission_wrong_data()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        $user->assignRole('patient');
        Sanctum::actingAs($user);
        Submission::factory()->create([
            'patient_id' => $user->id,
            'state' => Submission::STATUS_PENDING,
        ]);

        $newSubmissionInformation = [
            'symptoms' => '',
        ];
        $response = $this->putJson('/api/submission/1/patient', $newSubmissionInformation);
        $response->assertStatus(422);
    }
}
