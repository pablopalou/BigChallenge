<?php

namespace App\Http\Resources;

use App\Models\PatientInformation;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin PatientInformation
 */
class PatientInformationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'gender' => $this->gender,
            'height' => $this->height,
            'weight' => $this->weight,
            'birth' => $this->birth,
            'diseases' => $this->diseases,
            'previous_treatments' => $this->previous_treatments,
        ];
    }
}
