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

class GetPatientInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_see_his_her_information()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->has(PatientInformation::factory())->create();
        $user->assignRole('patient');
        $patientInformation = $user->patientInformation;
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/getPatientInformation/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Patient Information successfully',
                                'name' => $user->name,
                                'email' => $user->email,
                                'data' =>  ['height' => $patientInformation->height,
                                            'birth' => $patientInformation->birth,
                                            'weight' => $patientInformation->weight,
                                            'gender' => $patientInformation->gender,
                                            'diseases' => $patientInformation->diseases,
                                            'previous_treatments' => $patientInformation->previous_treatments, ],
                                ]);
    }

    public function test_doctor_can_see_his_her_patients_information()
    {
        (new RolesSeeder())->run();

        // create two submissions with different patients and try to see that patients
        Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);
        $submission2 = Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);

        $userPatient = $submission2->patient;
        $patientInformation = $userPatient->patientInformation;
        $submission2->doctor->assignRole('doctor');
        Sanctum::actingAs($submission2->doctor);

        $response = $this->getJson('/api/getPatientInformation/3');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Patient Information successfully',
                                'name' => $userPatient->name,
                                'email' => $userPatient->email,
                                'data' =>  ['height' => $patientInformation->height,
                                            'birth' => $patientInformation->birth,
                                            'weight' => $patientInformation->weight,
                                            'gender' => $patientInformation->gender,
                                            'diseases' => $patientInformation->diseases,
                                            'previous_treatments' => $patientInformation->previous_treatments, ],
                                ]);
    }

    public function test_doctor_can_NOT_others_patient_information()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->has(DoctorInformation::factory())->create();
        $user->assignRole('doctor');
        Sanctum::actingAs($user);

        // create submission and assign to other doctor and try to see THAT patient that is NOT MY patient
        Submission::factory()->create(['state' => Submission::STATUS_IN_PROGRESS]);

        $response = $this->getJson('/api/getPatientInformation/2');
        $response->assertStatus(403);
    }
}
