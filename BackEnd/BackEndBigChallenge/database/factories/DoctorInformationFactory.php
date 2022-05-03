<?php

namespace Database\Factories;

use App\Models\DoctorInformation;
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
            'speciality' => $this->faker->randomElement(DoctorInformation::specialities),
            'user_id' => User::factory(),
        ];
    }
}
