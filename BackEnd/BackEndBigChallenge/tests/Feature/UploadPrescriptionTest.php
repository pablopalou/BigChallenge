<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UploadPrescriptionTest extends TestCase
{
    use RefreshDatabase;
    public function test_prescription_save_successfully()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => UploadedFile::fake()->create('test.txt'),
        ]);
        $response->assertJson(['message' => 'File uploaded successfully']);
        $this->assertTrue(Storage::disk('do')->exists("pablopalou/{$response->json()['uuid']}"));
    }

    public function test_guest_tryng_to_upload_prescription()
    {
        $submission = Submission::factory()->inProgress()->create();
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => UploadedFile::fake()->create('test.txt'),
        ]);
        $response->assertStatus(401);
    }

    public function test_patient_trying_to_upload_prescription()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->patient);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => UploadedFile::fake()->create('test.txt'),
        ]);
        $response->assertStatus(403);
    }

    public function test_prescription_must_be_a_file()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => 'Not a file',
        ]);
        $response->assertStatus(422);
    }
    public function test_prescription_must_be_txt()
    {
        $submission = Submission::factory()->inProgress()->create();
        Sanctum::actingAs($submission->doctor);
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => UploadedFile::fake()->create('test.png'),
        ]);
        $response->assertStatus(422);
    }
}
