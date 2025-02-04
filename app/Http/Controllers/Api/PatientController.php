<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\BusinessHour;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Procedure;
use App\Models\Room;
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
    public function availableSlots(Request $request)
    {
        try {
            // validate the request
            $request->validate([
                'clinic_id' => 'required|exists:clinics,id',
                'procedure_id' => 'required|exists:procedures,id',
                'date' => 'required|date_format:Y-m-d',
            ]);

            // check if the procedure is available in the clinic
            $procedure = Procedure::with(['doctor'])->findOrFail($request->procedure_id);
            $clinic = Clinic::findOrFail($request->clinic_id);

            $room = Room::findOrFail($procedure->room_id);
            if ($room->clinic_id !== $clinic->id) {
                return response()->json([
                    'message' => 'Procedure not available in this clinic',
                ], Response::HTTP_NOT_FOUND);
            }

            $dayOfWeek = strtolower(Carbon::parse($request->date)->englishDayOfWeek);

            // getting the business hours for the clinic
            $businessHours = BusinessHour::where('clinic_id', $clinic->id)
                ->where('day', $dayOfWeek)
                ->first();

            if (!$businessHours) {
                return response()->json(['message' => 'Clinic is closed on this day'], Response::HTTP_OK);
            }


            // get the available slots based on the business hours and the duration.
            // exclude the slots that are already booked.
            // return the available slots
            // Generate time slots
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
