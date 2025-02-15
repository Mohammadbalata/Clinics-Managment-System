<?php

namespace App\Http\Requests;

use App\Rules\ValidBusinessHours;
use Illuminate\Foundation\Http\FormRequest;

class ClinicRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'office_name'       => 'required|string|min:3|max:255',
            'office_address'    => 'required|string|max:500',
            'timezone'          => 'required|timezone',
            'secretary_number'  => 'required|string|regex:/^\+?[0-9]{7,15}$/',
        ];

        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($days as $day) {
            $rules["business_hours.$day.open_time"] = ['required', 'date_format:H:i'];
            $rules["business_hours.$day.close_time"] = ['required', 'date_format:H:i', new ValidBusinessHours($day, 'close_time')];
            $rules["business_hours.$day.lunch_start"] = ['nullable', 'date_format:H:i', new ValidBusinessHours($day, 'lunch_start')];
            $rules["business_hours.$day.lunch_end"] = ['nullable', 'date_format:H:i', new ValidBusinessHours($day, 'lunch_end')];
        }

        return $rules;
    }
}