<?php

namespace Tests\Feature;

use App\Mail\PrescriptionMail;
use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile as HttpUploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PrescriptionNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mails_queued_successfully()
    {
        $this->markTestSkipped('This test is skipped');
        Mail::fake();
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => HttpUploadedFile::fake()->create('test.txt'),
        ]);
        Mail::assertQueued(PrescriptionMail::class, 1);
    }
}
