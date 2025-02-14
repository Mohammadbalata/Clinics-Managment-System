<?php

namespace App\Http\Requests;

use App\Models\Clinic;
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
        return  [
            'office_name'       => 'required|string|min:3|max:255',
            'office_address'    => 'required|string|max:500',
            'timezone'          => 'required|timezone',
            'secretary_number'  => 'required|string|regex:/^\+?[0-9]{7,15}$/',

            'business_hours.*.open_time'  => 'required|date_format:H:i',
            'business_hours.*.close_time'    => 'required|date_format:H:i|after:business_hours.*.open_time',

            'business_hours.*.lunch_start' => 'nullable|date_format:H:i|before:business_hours.*.lunch_end|after:business_hours.*.open_time',
            'business_hours.*.lunch_end'   => 'nullable|date_format:H:i|before:business_hours.*.close_time',
        ];
    }
}
