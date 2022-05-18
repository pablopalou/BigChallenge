<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
}
