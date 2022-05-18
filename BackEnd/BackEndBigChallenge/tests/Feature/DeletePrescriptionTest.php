<?php

namespace Tests\Feature;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeletePrescriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleted_file_successfully()
    {
        Storage::fake('do');
        Http::fake();
        $file = UploadedFile::fake()->create('test.txt');
        $uuid = (string) Str::uuid();
        Storage::put(
            "pablopalou/{$uuid}",
            file_get_contents($file),
        );
        // create submission with file as prescription
        $submission = Submission::factory()->inProgress()->create();
        // change state to ready and put prescription
        $submission->prescriptions = $file;
        $submission->state = Submission::STATUS_READY;
        $submission->save();

        Sanctum::actingAs($submission->doctor);
        $response = $this->deleteJson("/api/submission/{$submission->id}/prescription");
        $response->assertJson(['message' => 'Prescription deleted successfully']);
        // look how to assert that file is not anymore in storage
        // i suppose this should work
        $this->assertFalse(Storage::disk('do')->exists("pablopalou/{$response->json()['uuid']}"));
    }

    public function test_other_doctor_can_not_delete_prescription()
    {
        Storage::fake('do');
        $userDoctor = User::factory()->doctor()->patient()->create();
        $file = UploadedFile::fake()->create('test.txt');
        $uuid = (string) Str::uuid();
        Storage::put(
            "pablopalou/{$uuid}",
            file_get_contents($file),
        );
        $submission = Submission::factory()->inProgress()->create();
        $submission->prescriptions = $file;
        $submission->state = Submission::STATUS_READY;
        $submission->save();

        Sanctum::actingAs($userDoctor);
        $response = $this->deleteJson("/api/submission/{$submission->id}/prescription");
        $response->assertStatus(403);
    }

    public function test_guest_can_not_delete_prescription()
    {
        Storage::fake('do');
        $userDoctor = User::factory()->doctor()->create();
        $file = UploadedFile::fake()->create('test.txt');
        $uuid = (string) Str::uuid();
        Storage::put(
            "pablopalou/{$uuid}",
            file_get_contents($file),
        );
        $submission = Submission::factory()->inProgress()->create();
        $submission->prescriptions = $file;
        $submission->state = Submission::STATUS_READY;
        $submission->save();

        $response = $this->deleteJson("/api/submission/{$submission->id}/prescription");
        $response->assertStatus(401);
    }
}
