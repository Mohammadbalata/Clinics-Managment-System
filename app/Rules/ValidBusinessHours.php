<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidBusinessHours implements ValidationRule
{
    protected string $day;
    protected string $type; // open_time, close_time, lunch_start, lunch_end

    public function __construct(string $day, string $type)
    {
        $this->day = $day;
        $this->type = $type;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $day = $this->day;
        $openTime = request("business_hours.$day.open_time");
        $closeTime = request("business_hours.$day.close_time");
        $lunchStart = request("business_hours.$day.lunch_start");

        if ($this->type === 'close_time' && $openTime && $value <= $openTime) {
            $fail("Close time must be after open time for $day.");
        }

        if ($this->type === 'lunch_start') {
            if ($openTime && $value <= $openTime) {
                $fail("Lunch start must be after open time for $day.");
            }
            if ($closeTime && $value >= $closeTime) {
                $fail("Lunch start must be before close time for $day.");
            }
        }

        if ($this->type === 'lunch_end') {
            if (!$lunchStart) {
                $fail("Lunch end cannot be set without lunch start for $day.");
            }
            if ($lunchStart && $value <= $lunchStart) {
                $fail("Lunch end must be after lunch start for $day.");
            }
            if ($closeTime && $value >= $closeTime) {
                $fail("Lunch end must be before close time for $day.");
            }
        }
    }
}
