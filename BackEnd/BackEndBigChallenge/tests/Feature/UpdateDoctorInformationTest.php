<?php

namespace Tests\Feature;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateDoctorInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_doctor_information_successfully()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        $user->assignRole('doctor');
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $newDoctorInformation = [
            'grade' => '2',
            'speciality' => 'Cardiology',
        ];
        $response = $this->postJson('/api/updateDoctorInformation', $newDoctorInformation);
        $response->assertSuccessful();
        $this->assertDatabaseHas('doctor_information', $newDoctorInformation);
        $response->assertJson([
            'message' => 'Doctor information updated successfully',
        ]);
    }

    public function test_update_doctor_being_a_guest()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        $user->assignRole('doctor');
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        $newDoctorInformation = [
            'grade' => '2',
            'speciality' => 'Cardiology',
        ];
        $response = $this->postJson('/api/updateDoctorInformation', $newDoctorInformation);
        $response->assertStatus(401);
    }

    public function test_update_doctor_information_being_a_patient()
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        $user->assignRole('patient');
        PatientInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $newDoctorInformation = [
            'grade' => '2',
            'speciality' => 'Cardiology',
        ];
        $response = $this->postJson('/api/updateDoctorInformation', $newDoctorInformation);
        $response->assertStatus(403);
    }

    /**
     * @dataProvider invalidDoctorInformationProvider
     */
    public function test_update_doctor_information_invalid_data($data)
    {
        (new RolesSeeder())->run();
        $user = User::factory()->create();
        $user->assignRole('doctor');
        PatientInformation::factory()->create(['user_id' => $user->id]);
        DoctorInformation::factory()->create(['user_id' => $user->id]);
        Sanctum::actingAs($user);
        $response = $this->postJson('/api/updateDoctorInformation', $data);
        $response->assertStatus(422);
    }

    public function invalidDoctorInformationProvider()
    {
        return [
            ['no grade' => [
                'speciality' => 'Cardiology',
            ]],
            ['no speciality' => [
                'grade' => 3,
            ]],
            ['grade greater than 5' => [
                'grade' => 7,
                'speciality' => 'Cardiology',
            ]],
            ['grade smaller than 1' => [
                'grade' => 0,
                'speciality' => 'Cardiology',
            ]],
            ['grade not numeric' => [
                'grade' => 'i am a string',
                'speciality' => 'Cardiology',
            ]],
        ];
    }
}