<?php

use App\Http\Controllers\Api\AppointmentsController;
use App\Http\Controllers\Api\PatientController;
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

Route::get('testing', function () {
  return response()->json(['status' => 1]);
});

Route::get('/available-slots', [PatientController::class, 'getAvailableSlots']);

Route::group([
  'as' => 'patients.',
  'prefix' => 'patients',
], function () {
  Route::get('/search', [PatientController::class, 'search']);
  Route::get('/{query}/appointments', [PatientController::class, 'patientAppointments']);
});

Route::group([
  'as' => 'appointments.',
  'prefix' => 'appointments',
], function () {
  Route::post('/', [AppointmentsController::class, 'schedule']);
  Route::patch('/{id}/confirm', [AppointmentsController::class, 'confirm']);
  Route::patch('/{id}/cancel', [AppointmentsController::class, 'cancel']);
});
