<?php

namespace Tests\Feature;

use App\Models\PatientInformation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdatePatientInformationTest extends TestCase
{
    use RefreshDatabase;
    public function test_update_patient_information_successfully()
    {
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $newPatientInformation = [
            'gender' => 'male',
            'height' => '170',
            'weight' => '74',
            'birth' => '2000-12-06',
        ];
        $response = $this->postJson('/api/updatePatientInformation',$newPatientInformation);
        $response->assertSuccessful();
        $this->assertDatabaseHas('patient_information', $newPatientInformation);
        $response->assertJson([
            'message' => 'Patient information updated successfully',
        ]);
    }
    public function test_update_patient_being_a_guest()
    {
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        $newPatientInformation = [
            'gender' => 'male',
            'height' => '170',
            'weight' => '74',
            'birth' => '2000-12-06',
        ];
        $response = $this->postJson('/api/updatePatientInformation',$newPatientInformation);
        $response->assertStatus(401);
        $this->assertDatabaseMissing('patient_information', $newPatientInformation);
    }

    /**
     * @dataProvider invalidPatientInformationProvider
     */
    public function test_update_patient_with_invalid_data($data)
    {
        $user = User::factory()->create();
        PatientInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/updatePatientInformation',$data);
        $response->assertStatus(422);
    }

    public function invalidPatientInformationProvider()
    {
        return [
            ['no gender' => [
                'height' => 30,
                'weight' => 1,
                'birth' => '2008-12-11',
            ]],
            ['wrong gender' => [
                'gender' => 'pepe',
                'height' => 30,
                'weight' => 1,
                'birth' => '2008-12-11',
            ]],
            ['no height' => [
                'gender' => 'other',
                'weight' => 1,
                'birth' => '2008-12-11',
            ]],
            ['height not numeric' => [
                'gender' => 'other',
                'height' => 'small',
                'weight' => 1,
                'birth' => '2008-12-11',
            ]],
            ['height more than 230' => [
                'gender' => 'other',
                'height' => 330,
                'weight' => 1,
                'birth' => '2008-12-11',
            ]],
            ['no weight' => [
                'gender' => 'other',
                'height' => 30,
                'birth' => '2008-12-11',
            ]],
            ['weight not numeric' => [
                'gender' => 'other',
                'height' => 50,
                'weight' => 'not numeric',
                'birth' => '2008-12-11',
            ]],
            ['weight more than 300' => [
                'gender' => 'other',
                'height' => 70,
                'weight' => 450,
                'birth' => '2008-12-11',
            ]],
            ['no birth' => [
                'gender' => 'other',
                'height' => 30,
                'weight' => 1,
            ]],
            ['birth after today' => [
                'gender' => 'other',
                'height' => 30,
                'weight' => 20,
                'birth' => '2098-12-11',
            ]],
        ];
    }
}
