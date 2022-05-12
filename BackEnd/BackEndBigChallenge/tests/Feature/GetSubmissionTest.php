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

class GetSubmissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_submission_being_doctor()
    {
        (new RolesSeeder())->run();       
        $submission = Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $userDoctor = $submission->doctor;
        $userDoctor->assignRole('doctor');
        Sanctum::actingAs($userDoctor);
        // dd($submission);
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
        (new RolesSeeder())->run();       
        $submission = Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $userPatient = $submission->patient;
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
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
        (new RolesSeeder())->run();       
        Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $userPatient = User::factory()->has(PatientInformation::factory())->create();
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        $response = $this->getJson('/api/submission/1');
        $response->assertStatus(403);
    }

    public function test_get_submission_not_authorized_doctor()
    {
        (new RolesSeeder())->run();       
        Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $userDoctor = User::factory()->has(DoctorInformation::factory())->create();
        $userDoctor->assignRole('doctor');
        Sanctum::actingAs($userDoctor);
        $response = $this->getJson('/api/submission/1');
        $response->assertStatus(403);
    }
}
