<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'symptoms' => $this->faker->paragraph,
            'patient_id' => User::factory()->patient(),
            'state' => Submission::STATUS_PENDING,
        ];
    }

    public function inProgress()
    {
        return $this->state(function ($attributes) {
            return [
                'doctor_id' => User::factory()->doctor(),
                'state' => Submission::STATUS_IN_PROGRESS,
            ];
        });
    }

    public function ready()
    {
        return $this->state(function ($attributes) {
            return [
                'doctor_id' => User::factory()->doctor(),
                'state' => Submission::STATUS_READY,
            ];
        });
    }
}
