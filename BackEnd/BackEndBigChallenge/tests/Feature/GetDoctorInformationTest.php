<?php

namespace Tests\Feature;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class GetDoctorInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_doctor_can_get_his_her_information()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        $user->assignRole('doctor');
        $doctorInformation = DoctorInformation::factory()->create(['user_id' => $user->id]);
        PatientInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $response = $this->getJson('/api/getDoctorInformation/1');
        $response->assertSuccessful();
        $response->assertJson(['message' => 'Received Doctor Information successfully',
                                'data' =>  ['speciality' => $doctorInformation->speciality,
                                            'grade' => $doctorInformation->grade, ]
                                ]);
    }
}
