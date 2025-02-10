<?php

namespace App\Http\Controllers\Api;

use App\Enums\AppointmentStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Room;
use App\Services\SlotService;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
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

    public function patientAppointments(Request $request)
    {
        try {
            $request->validate([
                'query' => 'required|string|min:3',
            ]);

            $appointments = Appointment::where('phone_number', $request->input('query'))
                ->orWhere('first_name', $request->input('query'))
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

        $availableSlots = SlotService::generateAvailableSlots(
            $businessHours->open_time,
            $businessHours->close_time,
            $businessHours->lunch_start,
            $businessHours->lunch_end,
            $duration,
            $existingAppointments,
        );
        return response()->json(['data' => $availableSlots]);
    }

    
}
