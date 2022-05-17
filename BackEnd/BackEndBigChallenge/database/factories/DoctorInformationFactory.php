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
    /** @var \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User> */
    public function definition(): array
    {
        return [
            'grade' => $this->faker->numberBetween(1, 5),
            'speciality' => $this->faker->randomElement(DoctorInformation::specialities),
            'user_id' => User::factory(),
        ];
    }
}
