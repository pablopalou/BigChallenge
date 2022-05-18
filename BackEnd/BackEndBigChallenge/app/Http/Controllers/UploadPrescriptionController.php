<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPrescriptionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as SupportStr;

class UploadPrescriptionController
{
    public function __invoke(UploadPrescriptionRequest $request, Submission $submission): JsonResponse
    {
        $file = $request->file('prescriptions');
        // The fileName will be uuid, that is a unique identifier of the files.
        $uuid = (string) SupportStr::uuid();
        $folder = config('filesystems.disks.do.folder');
        Storage::put(
            "{$folder}/{$uuid}",
            file_get_contents($file)
        );

        // Now I have to update the submission
        $submission->prescriptions = $uuid;
        $submission->save();

        // @TODO: Make event to Notificate patient that a prescription has been made and dispatch it here.

        return response()->json([
            'message' => 'File uploaded successfully',
            'uuid' => $uuid,
        ]);
    }
}
