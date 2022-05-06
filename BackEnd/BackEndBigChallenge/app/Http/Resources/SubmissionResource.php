<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Submission;

/** @mixin Submission **/
class SubmissionResource extends JsonResource
{
    // In the submission I will have everything (the info of the doctor if he/she exists and
    // the info of the patient with the info of the submission).

    // Remember that doctor and patient are Users so we can access to the realationships DoctorInformation and PatientInfomation.

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'symptoms' => $this->symptoms,
            'state' => $this->state,
            'prescriptions' => $this->prescriptions,

            'doctor' => $this->when((!is_null($this->doctor)), function () {
                return new UserResource($this->doctor->loadMissing('doctorInformation'));
            }),
            'patient' => new UserResource($this->patient->loadMissing('patientInformation')),
        ];

        // What means parent here?
        // return parent::toArray($request);
    }
}
