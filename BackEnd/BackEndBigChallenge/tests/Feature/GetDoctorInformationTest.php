<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetDoctorInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_get_his_her_information()
    {
        $user = User::factory()->doctor()->patient()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/getDoctorInformation/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Doctor Information successfully',
                                'data' =>  ['speciality' => $user->doctorInformation->speciality,
                                            'grade' => $user->doctorInformation->grade, ],
                                ]);
    }

    public function test_patient_can_get_his_her_doctor_information()
    {
        $submission1 = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission1->patient);

        $response = $this->getJson('/api/getDoctorInformation/2');
        $response->assertSuccessful();
        $response->assertJson([
            'message' => 'Received Doctor Information successfully',
            'data' =>  ['speciality' => $submission1->doctor->doctorInformation->speciality,
                        'grade' => $submission1->doctor->doctorInformation->grade, ],
        ]);
    }

    public function test_patient_not_authorized_to_get_other_doctor_information()
    {
        $user = User::factory()->patient()->create();
        Submission::factory()->inProgress()->create();
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/getDoctorInformation/3');
        $response->assertStatus(403);
    }
}
