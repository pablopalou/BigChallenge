<?php

namespace Database\Factories;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'symptoms' => $this->faker->paragraph,
            'patient_id' => PatientInformation::factory()->create()->user,
            'state' => $this->faker->randomElement([Submission::STATUS_PENDING, Submission::STATUS_IN_PROGRESS, Submission::STATUS_READY]),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Submission $submission) {
            if ($submission->state !== Submission::STATUS_PENDING) {
                $doctor = DoctorInformation::factory()->create();
                PatientInformation::factory()->create(['user_id' => $doctor->user_id]);
                $submission->doctor_id = $doctor->user->id;

                // assign prescription only if status is ready
                if ($submission->state === Submission::STATUS_READY) {
                    $submission->prescriptions = $this->faker->paragraph;
                }
                $submission->save();
            }
        });
    }
}
