<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use App\Models\Patient;
use App\Models\Procedure;
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

        $procedureId = $request->input('procedure_id');
        $date = $request->input('date');
        $clinicId = $request->input('clinic_id');
        $procedure = Procedure::findOrFail($procedureId);
        $duration = $procedure->duration;
        $doctorId = $procedure->doctor_id;
        $roomId = $procedure->room_id;

        $dayOfWeek = Carbon::parse($date)->format('l');
        $businessHours = BusinessHour::where('clinic_id', $clinicId)
            ->where('day', $dayOfWeek)
            ->first();

        if (!$businessHours) {
            return response()->json(['error' => 'Clinic is closed on this day'], 404);
        }

        $businessStartTime = Carbon::parse($businessHours->open_time);
        $businessEndTime = Carbon::parse($businessHours->close_time);

        $existingAppointments = Appointment::where('doctor_id', $doctorId)
            ->orWhere('room_id', $roomId)
            ->whereDate('start_time', $date)
            ->get();

        $availableSlots = [];
        $currentSlotStart = $businessStartTime->copy();

        while ($currentSlotStart->copy()->addMinutes($duration)->lte($businessEndTime)) {
            $slotEnd = $currentSlotStart->copy()->addMinutes($duration);

            $conflict = $existingAppointments->contains(function ($appointment) use ($currentSlotStart, $slotEnd) {
                return $currentSlotStart->lt($appointment->end_time) && $slotEnd->gt($appointment->start_time);
            });

            if (!$conflict) {
                $availableSlots[] = $currentSlotStart->toTimeString('minute') . "-" . $slotEnd->toTimeString('minute');
            }
            $currentSlotStart->addMinutes($duration);
        }

        return response()->json(['data' => $availableSlots]);
    }
}
