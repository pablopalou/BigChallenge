<?php

namespace Tests\Feature;

use App\Mail\PrescriptionMail;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PrescriptionNotificationTest extends TestCase
{
    use RefreshDatabase;
    public function test_notification_sent_successfully()
    {
        Notification::fake();
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => HttpUploadedFile::fake()->create('test.txt'),
        ]);
        Notification::assertSentTo([User::first()], PrescriptionMail::class);
    }
}
