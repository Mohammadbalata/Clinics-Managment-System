<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Room;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class PatientController extends Controller
{
    public function search(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:3',
            ]);
            $patient = Patient::where('phone_number', $request->input('query'))
                ->orWhere('first_name', $request->input('query'))
                ->with('appointments')->get();

            if ($patient->isEmpty()) {
                return response()->json([
                    'message' => 'No patient found',
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'data' => $patient,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function patientAppointments($query)
    {
        try {
            if (strlen($query) < 1) {
                return response()->json([
                    'error' => 'Query must be at least 1 characters long.',
                ], Response::HTTP_BAD_REQUEST);
            }


            $appointments = Appointment::join('patients', 'appointments.patient_id', '=', 'patients.id')
                ->where('patients.phone_number', $query)
                ->orWhere('patients.id', $query)
                ->select('appointments.*')
                ->get();

            if ($appointments->isEmpty()) {
                return response()->json([
                    'message' => 'No appointments for this patient',
                ], Response::HTTP_NOT_FOUND);
            }
            return response()->json([
                'data' => $appointments,
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }




    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'procedure_id' => 'required|exists:procedures,id',
            'date' => 'required|date',
            'clinic_id' => 'required|exists:clinics,id',
        ]);

        $procedureId = $request->procedure_id;
        $date = $request->date;
        $clinicId = $request->clinic_id;
        $procedure = Procedure::findOrFail($procedureId);
        $room = Room::findOrFail($procedure->room_id);
        $status = $room->status;
        $duration = $procedure->duration;
        $doctorId = $procedure->doctor_id;
        $roomId = $procedure->room_id;
        $clinic = Clinic::findOrFail($clinicId);
        $dayOfWeek = Carbon::parse($date)->format('l');
        $businessHours = $clinic->businessHours()->where('day', $dayOfWeek)->first();

        // Ensure room belongs to the clinic
        if (!Room::where('id', $roomId)->where('clinic_id', $clinicId)->exists()) {
            return response()->json(['error' => 'Selected room does not belong to this clinic'], 400);
        }

        if (!$businessHours) {
            return response()->json(['error' => 'Clinic is closed on this day'], 404);
        }

        $existingAppointments = Appointment::where(function ($query) use ($doctorId, $roomId) {
            $query->where('doctor_id', $doctorId)
                ->orWhere('room_id', $roomId);
        })
            ->where('date', $date)
            ->where('status', '!=', AppointmentStatusEnum::Cancelled)
            ->get();

        $availableSlots = AppointmentService::generateAvailableSlots(
            $businessHours->open_time,
            $businessHours->close_time,
            $businessHours->lunch_start,
            $businessHours->lunch_end,
            $duration,
            $existingAppointments,
            $status
        );
        $convertedSlots = AppointmentService::convertTimeSlotsToTimezone($availableSlots, $clinic->timezone);
        return response()->json(['data' => $convertedSlots]);
    }
}
