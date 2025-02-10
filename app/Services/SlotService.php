<?php

namespace App\Services;

use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class SlotService
{
    public static function generateAvailableSlots($openTime, $closeTime, $lunchStart, $lunchEnd, $duration, $existingAppointments)
    {
        $businessStartTime = Carbon::parse($openTime);
        $businessEndTime = Carbon::parse($closeTime);
        $lunchStartTime = $lunchStart ? Carbon::parse($lunchStart) : null;
        $lunchEndTime = $lunchEnd ? Carbon::parse($lunchEnd) : null;

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
            $conflict = $existingAppointments->contains(function ($appointment) use ($currentSlotStart, $slotEnd) {
                return $currentSlotStart->lt($appointment->end_time) && $slotEnd->gt($appointment->start_time);
            });

            if (!$conflict) {
                $availableSlots[] = $currentSlotStart->toTimeString('minute') . "-" . $slotEnd->toTimeString('minute');
            }
            $currentSlotStart->addMinutes($duration);
        }

        return $availableSlots;
    }
}
