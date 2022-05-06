<?php

namespace App\Http\Resources;

use App\Models\DoctorInformation;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DoctorInformation
 */
class DoctorInformationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grade' => $this->grade,
            'speciality' => $this->speciality,
        ];
    }
}
