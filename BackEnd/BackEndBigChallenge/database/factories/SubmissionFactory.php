<?php

namespace Database\Factories;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\Submission;
use App\Models\User;
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

    public function configure()
    {
        return $this->afterCreating(function (Submission $submission) {
            if ($submission->state !== Submission::STATUS_PENDING) {
                $doctor = DoctorInformation::factory()->create();
                $submission->doctor_id = $doctor->user->id;

                // assign prescription only if status is ready
                if ($submission->state === Submission::STATUS_READY) {
                    $submission->prescriptions = $this->faker->paragraph;
                }
                $submission->save();
            }
        });
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => Submission::STATUS_PENDING,
            ];
        })->afterCreating(function (Submission $submission, User $user){
            $submission->save();
        });
    }

    public function inProgress(User $user)
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => Submission::STATUS_IN_PROGRESS,
            ];
        })->afterCreating(function (Submission $submission) use ($user){
            $submission->doctor_id = $user->id;
            $submission->save();
        });
    }

    public function ready(User $user)
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => Submission::STATUS_READY,
            ];
        })->afterCreating(function (Submission $submission) use ($user){
            $submission->doctor_id = $user->id;
            $submission->prescriptions = $this->faker->paragraph;
            $submission->save();
        });
    }
}
