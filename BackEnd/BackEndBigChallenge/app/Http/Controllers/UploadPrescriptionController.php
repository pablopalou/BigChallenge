<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadPrescriptionRequest;
use App\Models\Submission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str as SupportStr;

class UploadPrescriptionController extends Controller
{
    public function __invoke(UploadPrescriptionRequest $request, Submission $submission): JsonResponse
    {
        $file = $request->file('prescriptions');
        $fileName = (string) SupportStr::uuid();
        $folder = config('filesystems.disks.do.folder');
        Storage::put(
            "{$folder}/{$fileName}",
            file_get_contents($file)
        );
        
        // Now I have to update the submission
        $submission->prescriptions = $fileName;
        $submission->save();

        // @TODO: Make event to Notificate patient that a prescription has been made and dispatch it here. 
        
        return response()->json([
            'message' => 'File uploaded successfully',
        ]);
    }
}
