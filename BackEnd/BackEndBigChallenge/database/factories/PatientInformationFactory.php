<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PatientInformation>
 */
class PatientInformationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'height' => $this->faker->randomFloat(1, 30, 220),
            'weight' => $this->faker->randomFloat(2, 2, 230),
            'birth' => $this->faker->date(),
            'diseases' => $this->faker->text(255),
            'previous_treatments' => $this->faker->text(255),
            'user_id' => User::factory(),
        ];
    }
}
