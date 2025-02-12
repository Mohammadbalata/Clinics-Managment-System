<?php

namespace App\Services;

use Carbon\Carbon;

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
            if (self::isDuringLunchBreak($currentSlotStart, $slotEnd, $lunchStartTime, $lunchEndTime)) {
                $currentSlotStart = $lunchEndTime->copy();
                continue;
            }

            // Check for conflicts with existing appointments
            if (!self::hasAppointmentConflict($currentSlotStart, $slotEnd, $existingAppointments)) {
                $availableSlots[] = $currentSlotStart->toTimeString('minute') . "-" . $slotEnd->toTimeString('minute');
            }
            
            $currentSlotStart->addMinutes($duration);
        }

        return $availableSlots;
    }


    public static function isProcedureTimeAvailable($procedureTimeStart, $procedureDuration, $availableSlots): bool
    {
        $procedureStart = Carbon::parse($procedureTimeStart);
        $procedureEnd = $procedureStart->copy()->addMinutes($procedureDuration);

        foreach ($availableSlots as $slot) {
            list($slotStart, $slotEnd) = explode('-', $slot);
            $slotStart = Carbon::parse($slotStart);
            $slotEnd = Carbon::parse($slotEnd);

            if ($procedureStart->lt($slotEnd) && $procedureEnd->gt($slotStart)) {
                return true;
            }
        }

        return false;
    }

       private static function isDuringLunchBreak($slotStart, $slotEnd, $lunchStartTime, $lunchEndTime)
    {
        return $lunchStartTime && $lunchEndTime &&
            $slotStart->lt($lunchEndTime) && $slotEnd->gt($lunchStartTime);
    }

    private static function hasAppointmentConflict($slotStart, $slotEnd, $existingAppointments)
    {
        return $existingAppointments->contains(function ($appointment) use ($slotStart, $slotEnd) {
            return $slotStart->lt($appointment->end_time) && $slotEnd->gt($appointment->start_time);
        });
    }
}
