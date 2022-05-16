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

class GetSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_submission_being_doctor()
    {
        $submission = Submission::factory()->inProgress()->create();
        $userDoctor = $submission->doctor;
        Sanctum::actingAs($userDoctor);
        $response = $this->getJson('/api/submission/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Submission Successfully',
                                'data' =>  ['symptoms' => $submission->symptoms,
                                            'state' => $submission->state,
                                            'prescriptions' => $submission->prescriptions,
                                            'doctor' => ['id' => $submission->doctor_id],
                                            'patient' => ['id' => $submission->patient_id],
                                            ],
                                ]);
    }

    public function test_get_submission_being_patient()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->patient);
        $response = $this->getJson('/api/submission/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Submission Successfully',
                                'data' =>  ['symptoms' => $submission->symptoms,
                                            'state' => $submission->state,
                                            'prescriptions' => $submission->prescriptions,
                                            'doctor' => ['id' => $submission->doctor_id],
                                            'patient' => ['id' => $submission->patient_id],
                                            ],
                                ]);
    }

    public function test_get_submission_not_authorized_patient()
    {
        Submission::factory()->inProgress()->create();
        $userPatient = User::factory()->patient()->create();
        Sanctum::actingAs($userPatient);
        $response = $this->getJson('/api/submission/1');
        $response->assertStatus(403);
    }

    public function test_get_submission_not_authorized_doctor()
    {
        Submission::factory()->inProgress()->create();
        $userDoctor = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($userDoctor);
        $response = $this->getJson('/api/submission/1');
        $response->assertStatus(403);
    }
}
