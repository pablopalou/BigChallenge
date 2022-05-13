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

class GetListSubmissionTest extends TestCase
{
    use RefreshDatabase;
    public function test_get_all_patient_submissions()
    {
        (new RolesSeeder)->run();
        $userPatient = User::factory()->has(PatientInformation::factory())->create();
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        Submission::factory()->count(3)->create([
            'patient_id' => $userPatient->id,
        ]);
        Submission::factory()->count(7)->create();

        $response = $this->getJson('/api/submission');
        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
    }

    public function test_get_all_patient_submissions_pending()
    {
        (new RolesSeeder)->run();
        $userPatient = User::factory()->has(PatientInformation::factory())->create();
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        Submission::factory()->count(3)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_PENDING,
        ]);
        Submission::factory()->count(4)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_IN_PROGRESS,
        ]);
        Submission::factory()->count(5)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_READY,
        ]);
        Submission::factory()->count(15)->create();

        $response = $this->getJson("/api/submission?state=" . Submission::STATUS_PENDING);
        $response->assertSuccessful();
        $response->assertJsonCount(3, 'data');
    }

    public function test_get_all_patient_submissions_in_progress()
    {
        (new RolesSeeder)->run();
        $userPatient = User::factory()->has(PatientInformation::factory())->create();
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        Submission::factory()->count(3)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_PENDING,
        ]);
        Submission::factory()->count(4)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_IN_PROGRESS,
        ]);
        Submission::factory()->count(5)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_READY,
        ]);
        Submission::factory()->count(15)->create();

        $response = $this->getJson("/api/submission?state=" . Submission::STATUS_IN_PROGRESS);
        $response->assertSuccessful();
        $response->assertJsonCount(4, 'data');
    }

    public function test_get_all_patient_submissions_ready()
    {
        (new RolesSeeder)->run();
        $userPatient = User::factory()->has(PatientInformation::factory())->create();
        $userPatient->assignRole('patient');
        Sanctum::actingAs($userPatient);
        Submission::factory()->count(3)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_PENDING,
        ]);
        Submission::factory()->count(4)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_IN_PROGRESS,
        ]);
        Submission::factory()->count(5)->create([
            'patient_id' => $userPatient->id,
            'state' => Submission::STATUS_READY,
        ]);
        Submission::factory()->count(15)->create();

        $response = $this->getJson("/api/submission?state=" . Submission::STATUS_READY);
        $response->assertSuccessful();
        $response->assertJsonCount(5, 'data');
    }

    public function test_get_all_doctor_submissions()
    {
        (new RolesSeeder())->run();
        $userDoctor = User::factory()->has(DoctorInformation::factory())
                                    // ->has(Submission::factory()->pending($userDoctor)->count(3),'submissionsMade')
                                    ->has(Submission::factory()->inProgress()->count(7),'submissionsMade')
                                    ->has(Submission::factory()->ready()->count(10),'submissionsMade')
                                    ->has(Submission::factory()->inProgress()->count(4),'submissionsTaken')
                                    ->has(Submission::factory()->ready()->count(5),'submissionsTaken')
                                    ->create();
        $userDoctor->assignRole('doctor');
        Sanctum::actingAs($userDoctor);

        $response = $this->getJson('/api/submission');
        $response->assertSuccessful();
        $response->assertJsonCount(9, 'data');
    }

    public function test_get_doctor_submissions_ready(){

    }

    public function test_get_doctor_submissions_in_progress(){
        
    }

    public function test_get_doctor_submissions_as_patient(){
        
    }

    public function test_get_doctor_submissions_pending_as_patient(){
        
    }

    public function test_get_doctor_submissions_in_progress_as_patient(){
        
    }

    public function test_get_doctor_submissions_ready_as_patient(){
        
    }

    public function test_get_submissions_by_guest()
    {
        Submission::factory()->count(7)->create();

        $response = $this->getJson('/api/submission');
        $response->assertStatus(401);
    }
}
