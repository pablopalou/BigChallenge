<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetPrescriptionRequest;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class GetPrescriptionController extends Controller
{
    public function __invoke(GetPrescriptionRequest $request, Submission $submission)
    {
        $fileName = $submission['file'];
        $folder = config('filesystems.disks.do.folder');

        $url = Storage::temporaryUrl(
            "{$folder}/{$fileName}",
            now()->addWeek()
        );

        return response()->json([
            'status' => 200,
            'url' => $url,
        ]);
    }
}
