<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatusEnum;
use App\Events\AppointmentCreated;
use App\Events\AppointmentCancelled;
use App\Events\AppointmentConfirmed;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Room;
use App\Services\SlotService;
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
            $doctorId = $procedure->doctor_id;
            $roomId = $procedure->room_id;
            $clinic = Clinic::findOrFail($room->clinic_id);
            $dayOfWeek = Carbon::parse($request->date)->format('l');
            $businessHours = $clinic->businessHours()->where('day', $dayOfWeek)->first();
            $existingAppointments = Appointment::where(function ($query) use ($doctorId, $roomId) {
                $query->where('doctor_id', $doctorId)
                    ->orWhere('room_id', $roomId);
            })
                ->where('date', $request->date)
                ->where('status', '!=', AppointmentStatusEnum::Cancelled)
                ->get();
            $availableSlots = SlotService::generateAvailableSlots(
                $businessHours->open_time,
                $businessHours->close_time,
                $businessHours->lunch_start,
                $businessHours->lunch_end,
                $procedure->duration,
                $existingAppointments,
            );
            $startTime = Carbon::parse($request->start_time);
            $procedureTime = $startTime->toTimeString("minutes") . '-' . $startTime->addMinutes($procedure->duration)->toTimeString("minutes");
            if (!in_array($procedureTime, $availableSlots)) {
                return response()->json(['error' => 'Sorry, The Appointment is already booked'], 400);
            }



            // get the user, 
            if ($request->patient_id) {
                //validate patient_id
                $patient = Patient::findOrFail($request->patient_id);
                if (! $patient)
                    return response()->json(['error' => 'patient not found.'], Response::HTTP_NOT_FOUND);
                $request->merge(['patient_id' => $patient->id]);
            } else {
                $patientExists = Patient::where('phone_number', $request->patient['phone_number'])->first();
                // if the user doesn't exists, create it, 
                if (!$patientExists) {
                    $newPatient = Patient::create($request->patient);
                    $request->merge(['patient_id' => $newPatient->id]);
                } else {
                    // todo: ask mo about what should we do in this case. [1. make the patient to auth and return a patient  exists res, 2. use the founded patient]
                    // $request->merge(['patient_id' => $patientExists->id]);
                    return response()->json(['error' => 'patient already exists.'], Response::HTTP_BAD_REQUEST);
                }
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
            event(new AppointmentCreated($appointment));
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
            event(new AppointmentConfirmed($appointment));

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
                'cancellation_reason' => 'nullable|string|min:7|max:250'
            ]);

            // get the patient by phone number
            $patient = Patient::where('phone_number', $request->phone_number)->first();
            if (!$patient) {
                return response()->json(['error' => 'patient not found.'], Response::HTTP_NOT_FOUND);
            }

            // validate that the appointment is exists.
            $appointment = Appointment::find($id);
            // dd($appointment);
            if (!$appointment || $appointment->status !== AppointmentStatusEnum::Confirmed) {
                return response()->json(['error' => 'appointment not valid.'], Response::HTTP_NOT_FOUND);
            }

            // make sure that the user who booked this appointment.
            if ($appointment->patient->phone_number !== $request->phone_number) {
                return response()->json(['error' => 'you are not allowed to cancel this appointment.'], Response::HTTP_FORBIDDEN);
            }

            // update the appointment status to confirmed & returning res
            $appointment->status = AppointmentStatusEnum::Cancelled;
            $request->cancellation_reason && $appointment->cancellation_reason = $request->cancellation_reason;
            $appointment->save();
            // dd($appointment);
            event(new AppointmentCancelled($appointment));

            return response()->json([
                'message' => 'appointment cancelled.',
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
