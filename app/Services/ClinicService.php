<?php

namespace App\Services;

use App\Models\Clinic;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;

class ClinicService
{
    public function syncBusinessHours(Clinic $clinic, array $businessHours): void
    {
        $clinicTimezone = $clinic->timezone;
        foreach ($businessHours as $day => $hour) {
            $openTimeUTC = Carbon::parse($hour['open_time'], new CarbonTimeZone($clinicTimezone))->setTimezone('UTC')->format('H:i');
            $closeTimeUTC = Carbon::parse($hour['close_time'], new CarbonTimeZone($clinicTimezone))->setTimezone('UTC')->format('H:i');
            $lunchStartUTC = isset($hour['lunch_start']) ? Carbon::parse($hour['lunch_start'], new CarbonTimeZone($clinicTimezone))->setTimezone('UTC')->format('H:i') : null;
            $lunchEndUTC = isset($hour['lunch_end']) ? Carbon::parse($hour['lunch_end'], new CarbonTimeZone($clinicTimezone))->setTimezone('UTC')->format('H:i') : null;

            $clinic->businessHours()->updateOrCreate(
                ['day' => $day],
                [
                    'open_time' => $openTimeUTC,
                    'close_time' => $closeTimeUTC,
                    'lunch_start' => $lunchStartUTC,
                    'lunch_end' => $lunchEndUTC,
                ]
            );
        }
    }

    public function getBusinessHoursForClinic(Clinic $clinic): array
    {
        $clinicTimezone = $clinic->timezone;

        return $clinic->businessHours
            ->groupBy('day')
            ->map(function ($hours) use ($clinicTimezone) {
                return [
                    'open_time' => Carbon::parse($hours[0]->open_time, 'UTC')->setTimezone($clinicTimezone)->format('H:i'),
                    'close_time' => Carbon::parse($hours[0]->close_time, 'UTC')->setTimezone($clinicTimezone)->format('H:i'),
                    'lunch_start' => $hours[0]->lunch_start ? Carbon::parse($hours[0]->lunch_start, 'UTC')->setTimezone($clinicTimezone)->format('H:i') : null,
                    'lunch_end' => $hours[0]->lunch_end ? Carbon::parse($hours[0]->lunch_end, 'UTC')->setTimezone($clinicTimezone)->format('H:i') : null,
                ];
            })
            ->toArray();
    }
}
