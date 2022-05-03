<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\DoctorInformation;
use App\Models\PatientInformation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $arguments = $request->validated();
        $user = User::create($this->getUserPayload($arguments));

        // here we have to create the information of patient (and doctor if it is a doctor)
        // we create the patientInformation and associate it with the user
        $patientPayload = $this->getPatientPayload($arguments, $user);
        PatientInformation::create($patientPayload);

        // we have to create the doctor only if the role is Doctor
        if ($arguments['role'] === 'Doctor') {
            $doctorPayload = $this->getDoctorPayload($arguments, $user);
            DoctorInformation::create($doctorPayload);
        }

        event(new Registered($user));

        return response()->json([
            'status' => 200,
            'message' => 'User registered succesfully',
        ]);
    }

    public function getUserPayload(array $arguments): array
    {
        return [
            'name' => $arguments['name'],
            'email' => $arguments['email'],
            'password' => Hash::make($arguments['password']),
        ];
    }

    public function getPatientPayload(array $arguments, User $user): array
    {
        return [
            'gender' => $arguments['gender'],
            'height' => $arguments['height'],
            'weight' => $arguments['weight'],
            'birth' => $arguments['birth'],
            'diseases' => $arguments['diseases'],
            'previous_treatments' => $arguments['previous_treatments'],
            'user_id' => $user->id,
        ];
    }

    public function getDoctorPayload(array $arguments, User $user): array
    {
        return [
            'grade' => $arguments['grade'],
            'speciality' => $arguments['speciality'],
            'user_id' => $user->id,
        ];
    }
}
