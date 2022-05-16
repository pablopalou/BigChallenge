<?php

namespace Database\Factories;

use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * @return static
     */
    public function patient()
    {
        return $this->afterCreating(function (User $user) {
            try {
                Role::create([
                    'name' => 'patient',
                ]);
            } catch (\Exception $exception) {
                // Do nothing
            }

            $user->assignRole('patient');

            PatientInformation::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }

    /**
     * @return static
     */
    public function doctor()
    {
        return $this->afterCreating(function (User $user) {
            try {
                Role::create([
                    'name' => 'patient',
                ]);
            } catch (\Exception $exception) {
                // Do nothing
            }

            $user->assignRole('patient');

            DoctorInformation::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
