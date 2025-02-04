<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AppointmentsController extends Controller
{
    // TODO: standardize Api response 
    public function schedule(Request $request)
    {
        try {
            // validate req
            $request->validate(Appointment::rules($request));
            $procedure = Procedure::findOrFail($request->procedure_id); // to get the related doctor & room
            $room = Room::findOrFail($procedure->room_id); // to get related clinic

            // TODO: make sure that to exclude just the (pending, confirm) appointment, while we need to consider the cancelled appointments as available slots
            // check if the slot available. 
            // get the user, 
            $patientExists = Patient::where('phone_number', $request->patient['phone_number'])->first();

            // if the user doesn't exists, create it, 
            if (!$patientExists) {
                $newPatient = Patient::create($request->patient);
                $request->merge(['patient_id' => $newPatient->id]);
            } else {
                $request->merge(['patient_id' => $patientExists->id]);
            }

            // create an appointment record, with pending status,
            $startTime = Carbon::createFromFormat('H:i', $request->start_time);
            $endTime = $startTime->copy()->addMinutes($procedure->duration);
            $appointment = Appointment::create([
                'date' => $request->date,
                'status' => AppointmentStatusEnum::Pending,
                'patient_id' => $request->patient_id,
                'procedure_id' => $procedure->id,
                'doctor_id' => $procedure->doctor_id,
                'room_id' => $room->id,
                'clinic_id' => $room->clinic_id,
                'start_time' => $startTime->format('H:i'),
                'end_time' => $endTime->format('H:i'),
            ]);
            return response()->json([
                'message' => 'appointment schedule successfully.',
                'data' => $appointment,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function confirm(Request $request, string $id)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',
            ]);
            // get the patient by phone number
            $patient = Patient::where('phone_number', $request->phone_number)->first();
            if (!$patient) {
                return response()->json(['error' => 'patient not found.'], Response::HTTP_NOT_FOUND);
            }

            // validate that the appointment is exists.
            $appointment = Appointment::find($id);
            if (!$appointment) {
                return response()->json(['error' => 'appointment not found.'], Response::HTTP_NOT_FOUND);
            }

            // make sure that the user who booked this appointment.
            if ($appointment->patient->phone_number !== $request->phone_number) {
                return response()->json(['error' => 'you are not allowed to confirm this appointment.'], Response::HTTP_FORBIDDEN);
            }

            // update the appointment status to confirmed & returning res
            $appointment->status = AppointmentStatusEnum::Confirmed;
            $appointment->save();

            return response()->json([
                'message' => 'appointment confirmed.',
                'data' => $appointment,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cancel(Request $request, string $id)
    {
        try {
            $request->validate([
                'phone_number' => 'required|string|regex:/^\+?[0-9]{7,15}$/',
            ]);

            // get the patient by phone number
            $patient = Patient::where('phone_number', $request->phone_number)->first();
            if (!$patient) {
                return response()->json(['error' => 'patient not found.'], Response::HTTP_NOT_FOUND);
            }

            // validate that the appointment is exists.
            $appointment = Appointment::find($id);
            if (!$appointment || $appointment->status !== AppointmentStatusEnum::Confirmed) {
                return response()->json(['error' => 'appointment not valid.'], Response::HTTP_NOT_FOUND);
            }

            // make sure that the user who booked this appointment.
            if ($appointment->patient->phone_number !== $request->phone_number) {
                return response()->json(['error' => 'you are not allowed to confirm this appointment.'], Response::HTTP_FORBIDDEN);
            }

            // update the appointment status to confirmed & returning res
            $appointment->status = AppointmentStatusEnum::Cancelled;
            $appointment->save();

            return response()->json([
                'message' => 'appointment cancelled.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
