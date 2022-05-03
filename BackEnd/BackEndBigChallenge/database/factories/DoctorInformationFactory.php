<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoctorInformation>
 */
class DoctorInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'grade' => $this->faker->numberBetween(1, 5),
            'speciality' => $this->faker->randomElement(['Cardiology', 'Dermatology', 'Emergency medicine', 'Gastroenterology', 'Oncology', 'General', 'Neurology', 'Neurosurgery', 'Gynecology', 'Opthalmology', 'Pediatrics']),
            'user_id' => User::factory(),
        ];
    }
}
