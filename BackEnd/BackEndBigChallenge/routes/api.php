<?php

use App\Http\Controllers\CreateSubmissionController;
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

// @TODO: put routes grouped by roles

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class])->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', ResendVerificationEmailController::class)->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/login', LoginController::class)->middleware('guest');

Route::post('/register', RegisterController::class)->middleware('guest');

Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

Route::post('/createSubmission', CreateSubmissionController::class)->middleware('auth:sanctum');

Route::post('/updatePatientInformation', UpdatePatientInformationController::class)->middleware('auth:sanctum');

Route::post('/updateDoctorInformation', UpdateDoctorInformationController::class)->middleware('auth:sanctum', 'role:doctor');

Route::put('/submission/{submission}/patient', UpdateSymptomsController::class)->middleware('auth:sanctum');

Route::get('/getDoctorInformation/{doctorInformation}', GetDoctorInformationController::class)->middleware('auth:sanctum');

Route::get('/getPatientInformation/{patientInformation}', GetPatientInformationController::class)->middleware('auth:sanctum');

Route::get('/submission/{submission}', GetSubmissionController::class)->middleware('auth:sanctum');

Route::post('/submission/{submission}/take', TakeSubmissionController::class)->middleware('auth:sanctum', 'role:doctor');

Route::delete('/submission/{submission}', DeleteSubmissionController::class)->middleware('auth:sanctum', 'role:patient');

Route::get('/submission', ListSubmissionController::class)->middleware('auth:sanctum');
