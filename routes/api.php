<?php

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

// endpoints: 
// get the patient by phone number or name
Route::get('/patients/search', [PatientController::class, 'search']);

// get the patient appointments by phone number or name
Route::get('/patients/{query}/appointments', [PatientController::class, 'patientAppointments']);
Route::get('/available-slots', [PatientController::class, 'availableSlots']);

// [protected] POST /api/appointments/schedule -create a new appointment (patient_id || {first_name, last_name, phone_num}, doctor_id, procedure_id, date, time)
// [protected] PATCH /api/appointments/{id/confirm -confirm the appointment by  appointment_id
// [protected] PATCH /api/appointments/{id/cancel -cancel the appointment by appointment_id (appointment_id, reason)
// [not protected] GET /api/available-slots  -get available appointments by (doctor_id, date range, procedure_id (optional))
