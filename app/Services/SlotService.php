<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class SlotService
{
    public static function generateAvailableSlots($openTime, $closeTime, $lunchStart, $lunchEnd, $duration, $existingAppointments, $clinicTimezone)
    {
        $timezone = new CarbonTimeZone($clinicTimezone);

        $businessStartTime = Carbon::parse($openTime, $timezone);
        $businessEndTime = Carbon::parse($closeTime, $timezone);
        $lunchStartTime = $lunchStart ? Carbon::parse($lunchStart, $timezone) : null;
        $lunchEndTime = $lunchEnd ? Carbon::parse($lunchEnd, $timezone) : null;

        $availableSlots = [];
        $currentSlotStart = $businessStartTime->copy();

        while ($currentSlotStart->copy()->addMinutes($duration)->lte($businessEndTime)) {
            $slotEnd = $currentSlotStart->copy()->addMinutes($duration);

            // Skip lunch break
            if (
                $lunchStartTime && $lunchEndTime &&
                $currentSlotStart->lt($lunchEndTime) && $slotEnd->gt($lunchStartTime)
            ) {
                $currentSlotStart = $lunchEndTime->copy();
                continue;
            }

            // Check for conflicts with existing appointments
            $conflict = $existingAppointments->contains(function ($appointment) use ($currentSlotStart, $slotEnd, $timezone) {
                $appointmentStart = Carbon::parse($appointment->start_time, $timezone);
                $appointmentEnd = Carbon::parse($appointment->end_time, $timezone);
                return $currentSlotStart->lt($appointmentEnd) && $slotEnd->gt($appointmentStart);
            });

            if (!$conflict) {
                $availableSlots[] = $currentSlotStart->toTimeString('minute') . "-" . $slotEnd->toTimeString('minute');
            }

            $currentSlotStart->addMinutes($duration);
        }

        return $availableSlots;
    }
}
