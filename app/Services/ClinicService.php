<?php

namespace App\Services;

use App\Models\Clinic;
use Carbon\Carbon;

class ClinicService 
{
    public function syncBusinessHours(Clinic $clinic, array $businessHours): void
    {
        foreach ($businessHours as $day => $hour) {
            $clinic->businessHours()->updateOrCreate(
                ['day' => $day],
                [
                    'open_time' => $hour['open_time'],
                    'close_time' => $hour['close_time'],
                    'lunch_start' => $hour['lunch_start'] ?? null,
                    'lunch_end' => $hour['lunch_end'] ?? null,
                ]
            );
        }
    }

    public function getBusinessHoursForClinic(Clinic $clinic): array
    {
        return $clinic->businessHours
            ->groupBy('day')
            ->map(function ($hours) {
                return [
                    'open_time' => Carbon::parse($hours[0]->open_time)->format('H:i'),
                    'close_time' => Carbon::parse($hours[0]->close_time)->format('H:i'),
                    'lunch_start' => $hours[0]->lunch_start ? Carbon::parse($hours[0]->lunch_start)->format('H:i') : null,
                    'lunch_end' => $hours[0]->lunch_end ? Carbon::parse($hours[0]->lunch_end)->format('H:i') : null,
                ];
            })
            ->toArray();
    }
}
