<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\DoctorInformation;
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
