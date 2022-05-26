<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetPatientInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_can_see_his_her_information()
    {
        $user = User::factory()->patient()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/getPatientInformation/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Patient Information successfully',
                                'name' => $user->name,
                                'email' => $user->email,
                                'data' =>  ['height' => $user->patientInformation->height,
                                            'birth' => $user->patientInformation->birth,
                                            'weight' => $user->patientInformation->weight,
                                            'gender' => $user->patientInformation->gender,
                                            'diseases' => $user->patientInformation->diseases,
                                            'previous_treatments' => $user->patientInformation->previous_treatments, ],
                                ]);
    }

    public function test_doctor_can_see_his_her_patients_information()
    {
        // create two submissions with different patients and try to see that patient
        Submission::factory()->inProgress()->create();
        $submission2 = Submission::factory()->inProgress()->create();

        $patientInformation = $submission2->patient->patientInformation;
        Sanctum::actingAs($submission2->doctor);

        $response = $this->getJson("/api/getPatientInformation/{$patientInformation->user_id}");
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Patient Information successfully',
                                'name' => $submission2->patient->name,
                                'email' => $submission2->patient->email,
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
        $user = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($user);

        // create submission and assign to other doctor and try to see THAT patient that is NOT MY patient
        Submission::factory()->inProgress()->create();
        $response = $this->getJson('/api/getPatientInformation/2');
        $response->assertStatus(403);
    }
}
