<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ResendVerificationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_resend_verification_email_successfully()
    {
        Notification::fake();
        Sanctum::actingAs(User::factory()->create());
        $response = $this->postJson('/api/email/verification-notification');
        Notification::assertSentTo(User::first(), VerifyEmail::class);
        $response->assertJson(['status'=>200, 'message'=>'Verfication Email resended']);
    }

    public function test_resend_verification_email_being_guest()
    {
        Notification::fake();
        $response = $this->postJson('/api/email/verification-notification');
        Notification::assertNothingSent();
        $response->assertStatus(401);
    }
}
