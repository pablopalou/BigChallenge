<?php

namespace App\Http\Resources;

use App\Models\Submission;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubmissionResourceCollection extends ResourceCollection
{
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
