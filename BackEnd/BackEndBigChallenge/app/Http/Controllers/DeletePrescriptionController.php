<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeletePrescriptionRequest;
use App\Models\Submission;
use App\Services\CdnService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DeletePrescriptionController
{
    public function __invoke(DeletePrescriptionRequest $request, Submission $submission, CdnService $cdnService): JsonResponse
    {
        $uuid = $submission->prescriptions;
        $folder = config('filesystems.disk.do.folder');

        $submission->prescriptions = null;
        $submission->save();

        Storage::delete("{$folder}/{$uuid}");
        $cdnService->purge($uuid);

        return response()->json([
            'message' => 'Prescription deleted successfully',
            'uuid' => $uuid,
        ]);
    }
}
