<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_log_out_succesfully()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson('/api/logout');
        $response->assertJson([
            'status' => 200,
            'message' => 'User logged out succesfully',
        ]);
    }

    public function test_log_out_being_a_guest()
    {
        $response = $this->postJson('/api/logout');
        $response->assertStatus(401);
    }
}
