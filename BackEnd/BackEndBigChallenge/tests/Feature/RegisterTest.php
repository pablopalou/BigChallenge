<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    // @TODO: Verify email notifications (when register and when fails)

    /**
     * @dataProvider patientCredentialsProvider
     */
    public function test_register_patient_succesfully($user)
    {
        $response = $this->postJson('/api/register', $user);
        $response->assertSuccessful();
        $this->assertDatabaseHas('users', ['email' => 'pablitopaloutdm@gmail.com']);
        $this->assertDatabaseHas('patient_information', ['height' => '170', 'weight' => '74']);
        $this->assertDatabaseMissing('doctor_information', []);
        $response->assertJson([
            'status' => 200,
            'message' => 'User registered succesfully',
        ]);
    }

    public function patientCredentialsProvider(): array
    {
        return [
            ['validPatient' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Patient',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => null,
                'speciality' => null,
            ]],
        ];
    }

    /**
     * @dataProvider doctorCredentialsProvider
     */
    public function test_register_doctor_succesfully($user)
    {
        $response = $this->postJson('/api/register', $user);
        $response->assertSuccessful();
        $this->assertDatabaseHas('users', ['email' => 'pablitopaloutdm@gmail.com']);
        $this->assertDatabaseHas('patient_information', ['height' => '170', 'weight' => '74']);
        $this->assertDatabaseHas('doctor_information', ['grade' => 2, 'speciality' => 'Cardiology']);
        $response->assertJson([
            'status' => 200,
            'message' => 'User registered succesfully',
        ]);
    }

    public function doctorCredentialsProvider(): array
    {
        return [
            ['validDoctor' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
        ];
    }

    /**
     * @dataProvider validUsersCredentialsProvider
     */
    public function test_register_two_times($user)
    {
        $this->postJson('/api/register', $user);
        $response2 = $this->postJson('/api/register', $user);
        $response2->assertStatus(422);
    }

    public function validUsersCredentialsProvider(): array
    {
        return [
            ['validDoctor' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['validPatient' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Patient',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => null,
                'speciality' => null,
            ]],
        ];
    }

    public function test_register_if_already_logged()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson('/api/register');
        $response->assertStatus(302);
    }

    /**
     * @dataProvider invalidCredentialsProvider
     */
    public function test_invalid_credentials($user)
    {
        $response = $this->postJson('/api/register', $user);
        $response->assertStatus(422);
    }

    public function invalidCredentialsProvider(): array
    {
        return [
            ['noName' => [
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noPass' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noPassConfirmation' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['wrongPassConfirmation' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'wronggggpassword',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noRole' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noGender' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noHeight' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noWeight' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noBirth' => [
                'name' => 'pablito',
                'email' => 'pablitopaloutdm@gmail.com',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
            ['noEmail' => [
                'name' => 'pablito',
                'password' => 'password',
                'password_confirmation' => 'password',
                'role' => 'Doctor',
                'gender' => 'male',
                'height' => '170',
                'weight' => '74',
                'birth' => '2000-12-06',
                'diseases' => 'diabethes',
                'previous_treatments' => 't4',
                'grade' => 2,
                'speciality' => 'Cardiology',
            ]],
        ];
    }
}
