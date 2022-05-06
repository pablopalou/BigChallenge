<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\PatientInformation;

/**
 * @mixin PatientInformation
 */
class PatientInformationResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'gender' => $this->gender,
            'height' => $this->height,
            'weight' => $this->weight,
            // see if this format is correct
            'birth' => Carbon::parse($this->birth)->format('d-m-Y'),
            'diseases' => $this->diseases,
            'previous_treatments' => $this->previous_treatments,
        ];
    }
}
