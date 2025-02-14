<?php

namespace App\Http\Requests;

use App\Constants\DoctorSpecialties;
use App\Models\Doctor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorRequest extends FormRequest
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
        $id = $this->route('doctor');
        return  [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'specialty'    => 'required|in:' . implode(',', DoctorSpecialties::LIST),
            'email' => ['required', 'email', Rule::unique('doctors')->ignore($id)],
            'phone' => 'required|numeric',
            'clinic_id' => 'required|exists:clinics,id'
        ];
    }
}
