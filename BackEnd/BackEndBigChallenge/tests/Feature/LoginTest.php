<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_log_in_successfully()
    {
        $user = User::factory()->create(['password' => Hash::make('pablooo'),]);
        $response = $this->postJson('/api/login', ['email'=> $user->email, 'password'=>'pablooo']);
        $response->assertSuccessful();
        $response->assertJson([
            'status' => 200,
            'message' => 'User logged succesfully']);
    }
}
