<?php

use App\Http\Controllers\CreateSubmissionController;
use App\Http\Controllers\DeletePrescriptionController;
use App\Http\Controllers\DeleteSubmissionController;
use App\Http\Controllers\GetDoctorInformationController;
use App\Http\Controllers\GetPatientInformationController;
use App\Http\Controllers\GetSubmissionController;
use App\Http\Controllers\ListSubmissionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResendVerificationEmailController;
use App\Http\Controllers\TakeSubmissionController;
use App\Http\Controllers\UpdateDoctorInformationController;
use App\Http\Controllers\UpdatePatientInformationController;
use App\Http\Controllers\UpdateSymptomsController;
use App\Http\Controllers\UploadPrescriptionController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// @TODO: create view for email not verified yet (when y add the midlleware verified and a user attempts to access, then they will be redirected to the verification.notice named route.)

Route::middleware(['guest:sanctum'])->group(function () {
    Route::post('/login', LoginController::class); //Postman
    Route::post('/register', RegisterController::class); //Postman
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', LogoutController::class); //Postman
    Route::post('/createSubmission', CreateSubmissionController::class); //Postman
    Route::post('/updatePatientInformation', UpdatePatientInformationController::class); //Postman
    Route::get('/submission', ListSubmissionController::class);
    Route::put('/submission/{submission}/patient', UpdateSymptomsController::class); //Postman
    Route::get('/getDoctorInformation/{doctorInformation}', GetDoctorInformationController::class); //Postman (cambiar por user id)
    Route::get('/getPatientInformation/{patientInformation}', GetPatientInformationController::class); //Postman (cambiar por user id)
    Route::get('/submission/{submission}', GetSubmissionController::class); //Postman

    Route::middleware(['role:doctor'])->group(function () {
        Route::post('/updateDoctorInformation', UpdateDoctorInformationController::class);
        Route::post('/submission/{submission}/take', TakeSubmissionController::class);
        Route::post('/submission/{submission}/prescription', UploadPrescriptionController::class);
        Route::delete('/submission/{submission}/prescription', DeletePrescriptionController::class);
    });

    Route::middleware(['role:patient'])->group(function () {
        Route::delete('/submission/{submission}', DeleteSubmissionController::class)->middleware('auth:sanctum', 'role:patient');
    });
});

Route::middleware(['auth', 'signed'])->group(function () {
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class])->middleware(['auth', 'signed'])->name('verification.verify');
});

Route::middleware(['auth', 'throttle:6,1'])->group(function () {
    Route::post('/email/verification-notification', ResendVerificationEmailController::class)->name('verification.send');
});
