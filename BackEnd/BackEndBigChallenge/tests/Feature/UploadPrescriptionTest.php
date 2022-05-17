<?php

namespace Tests\Feature;

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadPrescriptionTest extends TestCase
{
    use RefreshDatabase;
    public function test_prescription_save_successfully()
    {
        $submission = Submission::factory()->inProgress()->create();
        Storage::fake('do');
        $response = $this->postJson("/api/submission/{$submission->id}/prescription", [
            'prescriptions' => UploadedFile::fake()->create('test.txt'),
        ]);
        // $this->assertFileExists(Storage::disk('do')->"pablopalou/text.txt");
        $this->assertTrue(Storage::disk('do')->exists("pablopalou/text.txt"));
        // Storage::disk('do')->assertExists("pablopalou/text.txt");
    }
}
